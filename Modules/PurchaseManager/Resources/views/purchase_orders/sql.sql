create view v_purchase_delivery_certificate_supplier as
SELECT purchases.id, purchases.purchase_no,CONCAT(projects.unique_reference_no,'-',
IF(purchases.purchase_no < 10, CONCAT('00', purchases.purchase_no),
IF( purchases.purchase_no < 100, CONCAT('0', purchases.purchase_no), purchases.purchase_no )
)
) as unique_reference_no ,  purchases.delivery_date, purchases.delivery_time,purchases.delivery_address, purchases.notes,purchases.project_id,purchases.supplier_id,purchases.revision_no,purchases.grand_total,
purchases.created_at,users.supplier_name,projects.company_id
FROM purchases
JOIN projects ON purchases.project_id=projects.id
JOIN users ON users.id=purchases.supplier_id;