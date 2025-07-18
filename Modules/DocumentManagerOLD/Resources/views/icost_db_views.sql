create or replace view v_purchase_note_certificate  AS  
(
select 'public/uploads/purchase_certificate' as storage,'Purchase Order' as module,'Certificate' as category , p.project_id, p.purchase_no, c.id,CONCAT('A',c.id) AS 'rowcount', c.certificate as cer_or_delnote , c.created, 'dummy note in certificate' as note,substr(trim(substring_index(c.certificate, '.', -1)),1,4) doc_type from purchases p, purchase_certificate c where p.id = c.purchase_no
union ALL
select 'public/uploads/purchase_deliverynote' as storage,'Purchase Order' as module,'Delivery Note' as category, p.project_id, p.purchase_no, d.id,CONCAT('B',d.id) AS 'rowcount', d.delivery_note as cer_or_delnote, d.created, d.note ,substr(trim(substring_index(d.delivery_note, '.', -1)),1,4) doc_type from purchases p, purchase_deliverynote d where p.id = d.purchase_no
union ALL
select 'public/uploads/purchase_invoice_file' as storage,'Purchase Order' as module,'Invoice File' as category, p.project_id, p.purchase_no,  d.id,CONCAT('C',d.id) AS 'rowcount', d.invoice_file as cer_or_delnote, d.created_at, 'dummy note in certificate' as note ,substr(trim(substring_index(d.invoice_file, '.', -1)),1,4) doc_type from purchases p, purchase_invoices d where p.id = d.purchase_id 
union ALL
select 'storage/app/public/purchases' as storage,'Purchase Order' as module,'Image' as category, p.project_id, p.purchase_no,  d.id,CONCAT('D',d.id) AS 'rowcount', d.photo as cer_or_delnote, d.created_at, 'dummy note in certificate' as note ,substr(trim(substring_index(d.photo, '.', -1)),1,4) doc_type from purchases p, purchase_orders d where p.id = d.purchase_id And d.photo !="" 
union ALL
select 'storage/app/public/quotations' as storage,'RFQs' as module,'Image' as category, p.project_id, p.id, d.id,CONCAT('E',d.id) AS 'rowcount', d.photo as cer_or_delnote, d.created_at, 'dummy note in certificate' as note ,substr(trim(substring_index(d.photo, '.', -1)),1,4) doc_type from quotations p, quotation_materials d where p.id = d.quotation_id And d.photo !=""
union ALL
select CONCAT('storage/app/public/timesheet_files/',d.category) as storage,'TimesheetManager' as module,d.category as category, p.project_id, p.id,  d.id,CONCAT('F',d.id) AS 'rowcount', d.file as cer_or_delnote, d.created_at, 'dummy note in certificate' as note ,substr(trim(substring_index(d.file, '.', -1)),1,4) doc_type from staff_timesheets p, staff_timesheet_files d where p.id = d.staff_timesheet_id
union ALL
select 'storage/app/public/timesheet_files' as storage,'LabourTimesheetManager' as module,d.category as category, p.project_id, p.id, d.id,CONCAT('G',d.id) AS 'rowcount', d.file as cer_or_delnote, d.created_at, 'dummy note in certificate' as note ,substr(trim(substring_index(d.file, '.', -1)),1,4) doc_type from labour_timesheets p, labour_timesheet_files d where p.id = d.labour_timesheet_id
union ALL
select 'storage/app/public/' as storage,'Company/User/Supplier Manager' as module,'Avatars' as category, p.id, p.id, d.id, ROW_NUMBER() OVER(ORDER BY created_at ASC) AS 'rowcount', d.avatar as cer_or_delnote, d.created_at, 'dummy note in certificate' as note ,substr(trim(substring_index(d.avatar, '.', -1)),1,4) doc_type from projects p, users d where p.company_id = d.company_id And d.avatar !=""
)