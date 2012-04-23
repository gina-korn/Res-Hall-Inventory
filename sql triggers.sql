delimiter |

CREATE OR REPLACE TRIGGER log_transactions BEFORE UPDATE ON CHECKOUT
FOR EACH ROW
BEGIN
	IF (new.date_checked_in IS NOT NULL) THEN
		INSERT INTO old_transactions
		VALUES(old.order_number, old.student_id, new.date_checked_in);
	
		UPDATE ITEM
		SET checked_out_count = checked_out_count + 1
		WHERE item_id = (SELECT item_id
						 FROM LINEITEM
						 WHERE LINEITEM.order_number = old.order_number);
		
		UPDATE USER
		SET transaction_count = transaction_count + 1
		WHERE USER.student_id = old.student_id;
		
		DELETE
		FROM LINEITEM
		WHERE LINEITEM.order_number = old.order_number;
	END IF
END|

CREATE OR REPLACE TRIGGER log_transactions_2 AFTER INSERT ON old_transactions
BEGIN
	DELETE
	FROM CHECKOUT
	WHERE new.order_number = CHECKOUT.order_number;
END|

delimiter ;
		
CREATE OR REPLACE VIEW most_active_users
AS
SELECT CONCAT(fname, " ", lname) AS name, student_id, transaction_count
FROM USER
WHERE transaction_count > 0
ORDER BY transaction_count DESC;

CREATE OR REPLACE VIEW popular_categories
AS
select CATEGORY.name, sum(checked_out_count) AS times_checked_out
from CATEGORY JOIN ITEM USING (category_id)
group by category_id
order by times_checked_out DESC;

lost/damaged reports

CREATE OR REPLACE VIEW 