CREATE OR REPLACE VIEW items_reserved_next_24h
AS
SELECT i.name as "Item", CONCAT(u.f_name, " ", u.l_name) AS "Reserved By", c.date_checked_out AS "Reservation Date"
FROM CHECKOUT c, ITEM i, LINEITEM l, USER u
WHERE ((c.order_number = l.order_number) and (l.item_id = i.item_id) and (c.student_id = u.student_id))
and ((c.date_checked_out < adddate(now(), 1)) and UPPER(c.checkout_type) = 'RESERVE')

CREATE OR REPLACE VIEW all_items_reserved
AS
SELECT i.name as "Item", CONCAT(u.f_name, " ", u.l_name) AS "Reserved By", c.date_checked_out AS "Reservation Date"
FROM CHECKOUT c, ITEM i, LINEITEM l, USER u
WHERE ((c.order_number = l.order_number) and (l.item_id = i.item_id) and (c.student_id = u.student_id))
and ((c.date_checked_out > now()) and UPPER(c.checkout_type) = 'RESERVE')
