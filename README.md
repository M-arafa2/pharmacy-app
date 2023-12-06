# pharmacy-app
the application have two parts :
first the Api to create users-manage addresses-recieve orders(prescription images)-manage orders.
second part is the dashboard which serve three roles.
doctor can only manage orders.
pharmacyowner can manage his own orders and his own doctors.
admin can manage all pharmacies and all orders.

## cycle
user will login recieve token create new order(uploading prescriptions)
once order placed the application will choose the nearest pharmacy with highest priority and assign the order to it.
then the assigned pharmacy owner or doctors can show the ordered prescriptions and fill the order with the required medications
then payment link will be sent to the user with the details and price using stripe.
then the user either cancel or confirm the payment.

## plugins/packages used:
Datatables for managing datatables on the dashboard.
chart js 
adminlte
stripe as payment method
laravel sanctum for handeling the api auth 
spite to handle authortization on dashboard

 
