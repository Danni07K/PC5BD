-- PL/SQL Procedures for Biblioteca Virtual
-- Sistema de Préstamo de Libros

-- Package for loan management procedures
CREATE OR REPLACE PACKAGE loan_package AS
    -- Procedure to create a new loan
    PROCEDURE create_loan(
        p_user_id IN NUMBER,
        p_book_id IN NUMBER,
        p_due_date IN DATE,
        p_notes IN VARCHAR2 DEFAULT NULL,
        p_success OUT NUMBER,
        p_message OUT VARCHAR2
    );

    -- Procedure to return a book
    PROCEDURE return_book(
        p_loan_id IN NUMBER,
        p_return_date IN DATE,
        p_notes IN VARCHAR2 DEFAULT NULL,
        p_success OUT NUMBER,
        p_message OUT VARCHAR2
    );

    -- Function to check if book is available
    FUNCTION is_book_available(p_book_id IN NUMBER) RETURN BOOLEAN;

    -- Function to check if loan is active
    FUNCTION is_loan_active(p_loan_id IN NUMBER) RETURN BOOLEAN;

    -- Procedure to update overdue loans
    PROCEDURE update_overdue_loans;

END loan_package;
/

-- Package body implementation
CREATE OR REPLACE PACKAGE BODY loan_package AS

    -- Function to check if book is available
    FUNCTION is_book_available(p_book_id IN NUMBER) RETURN BOOLEAN IS
        v_available_quantity NUMBER;
    BEGIN
        SELECT available_quantity INTO v_available_quantity
        FROM books
        WHERE id = p_book_id;

        RETURN v_available_quantity > 0;
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
    END is_book_available;

    -- Function to check if loan is active
    FUNCTION is_loan_active(p_loan_id IN NUMBER) RETURN BOOLEAN IS
        v_status VARCHAR2(20);
    BEGIN
        SELECT status INTO v_status
        FROM loans
        WHERE id = p_loan_id;

        RETURN v_status = 'active';
    EXCEPTION
        WHEN NO_DATA_FOUND THEN
            RETURN FALSE;
        WHEN OTHERS THEN
            RETURN FALSE;
    END is_loan_active;

    -- Procedure to create a new loan
    PROCEDURE create_loan(
        p_user_id IN NUMBER,
        p_book_id IN NUMBER,
        p_due_date IN DATE,
        p_notes IN VARCHAR2 DEFAULT NULL,
        p_success OUT NUMBER,
        p_message OUT VARCHAR2
    ) IS
        v_loan_id NUMBER;
    BEGIN
        -- Check if book is available
        IF NOT is_book_available(p_book_id) THEN
            p_success := 0;
            p_message := 'El libro no está disponible para préstamo.';
            RETURN;
        END IF;

        -- Check if due date is in the future
        IF p_due_date <= SYSDATE THEN
            p_success := 0;
            p_message := 'La fecha de vencimiento debe ser futura.';
            RETURN;
        END IF;

        -- Create the loan
        INSERT INTO loans (user_id, book_id, loan_date, due_date, status, notes)
        VALUES (p_user_id, p_book_id, SYSDATE, p_due_date, 'active', p_notes)
        RETURNING id INTO v_loan_id;

        -- Update book availability
        UPDATE books
        SET available_quantity = available_quantity - 1
        WHERE id = p_book_id;

        -- Commit transaction
        COMMIT;

        p_success := 1;
        p_message := 'Préstamo creado exitosamente con ID: ' || v_loan_id;

    EXCEPTION
        WHEN OTHERS THEN
            ROLLBACK;
            p_success := 0;
            p_message := 'Error al crear el préstamo: ' || SQLERRM;
    END create_loan;

    -- Procedure to return a book
    PROCEDURE return_book(
        p_loan_id IN NUMBER,
        p_return_date IN DATE,
        p_notes IN VARCHAR2 DEFAULT NULL,
        p_success OUT NUMBER,
        p_message OUT VARCHAR2
    ) IS
        v_book_id NUMBER;
        v_current_notes VARCHAR2(4000);
    BEGIN
        -- Check if loan exists and is active
        IF NOT is_loan_active(p_loan_id) THEN
            p_success := 0;
            p_message := 'El préstamo no existe o ya fue devuelto.';
            RETURN;
        END IF;

        -- Get book ID and current notes
        SELECT book_id, notes INTO v_book_id, v_current_notes
        FROM loans
        WHERE id = p_loan_id;

        -- Update loan status
        UPDATE loans
        SET return_date = p_return_date,
            status = 'returned',
            notes = CASE
                WHEN v_current_notes IS NOT NULL AND p_notes IS NOT NULL
                THEN v_current_notes || ' | ' || p_notes
                WHEN p_notes IS NOT NULL
                THEN p_notes
                ELSE v_current_notes
            END
        WHERE id = p_loan_id;

        -- Update book availability
        UPDATE books
        SET available_quantity = available_quantity + 1
        WHERE id = v_book_id;

        -- Commit transaction
        COMMIT;

        p_success := 1;
        p_message := 'Libro devuelto exitosamente.';

    EXCEPTION
        WHEN OTHERS THEN
            ROLLBACK;
            p_success := 0;
            p_message := 'Error al devolver el libro: ' || SQLERRM;
    END return_book;

    -- Procedure to update overdue loans
    PROCEDURE update_overdue_loans IS
    BEGIN
        UPDATE loans
        SET status = 'overdue'
        WHERE status = 'active'
        AND due_date < SYSDATE;

        COMMIT;
    EXCEPTION
        WHEN OTHERS THEN
            ROLLBACK;
            RAISE;
    END update_overdue_loans;

END loan_package;
/

-- Create a job to update overdue loans daily
BEGIN
    DBMS_SCHEDULER.CREATE_JOB(
        job_name        => 'UPDATE_OVERDUE_LOANS_JOB',
        job_type        => 'PLSQL_BLOCK',
        job_action      => 'BEGIN loan_package.update_overdue_loans; END;',
        repeat_interval => 'FREQ=DAILY; BYHOUR=1',
        enabled         => TRUE,
        comments        => 'Job to update overdue loans status daily'
    );
EXCEPTION
    WHEN OTHERS THEN
        NULL; -- Job might already exist
END;
/

-- Create views for reports
CREATE OR REPLACE VIEW v_overdue_loans AS
SELECT
    l.id as loan_id,
    u.name as user_name,
    u.email as user_email,
    b.title as book_title,
    b.author as book_author,
    l.loan_date,
    l.due_date,
    l.notes
FROM loans l
JOIN users u ON l.user_id = u.id
JOIN books b ON l.book_id = b.id
WHERE l.status = 'overdue'
ORDER BY l.due_date;

CREATE OR REPLACE VIEW v_most_borrowed_books AS
SELECT
    b.id,
    b.title,
    b.author,
    b.category,
    COUNT(l.id) as loan_count,
    b.quantity,
    b.available_quantity
FROM books b
LEFT JOIN loans l ON b.id = l.book_id
GROUP BY b.id, b.title, b.author, b.category, b.quantity, b.available_quantity
ORDER BY loan_count DESC;

-- Grant permissions (adjust as needed)
-- GRANT EXECUTE ON loan_package TO your_laravel_user;
-- GRANT SELECT ON v_overdue_loans TO your_laravel_user;
-- GRANT SELECT ON v_most_borrowed_books TO your_laravel_user;


