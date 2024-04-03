<?php


// Check if the request is a Shiprocket webhook notification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json') {
    // Process the webhook payload
    $webhookData = json_decode(file_get_contents('php://input'), true);

    // Implement your logic to handle the webhook payload
    // Example: Update the order status based on the tracking event
    // $orderId = $webhookData['order_id'];
    // $trackingEvent = $webhookData['event'];
    // $newStatus = getStatusFromTrackingEvent($trackingEvent);
    // updateOrderStatus($orderId, $newStatus);

    // Respond with a 200 OK status to acknowledge receipt of the webhook
    http_response_code(200);
    exit;
}

// Fetch orders from API
$apiUrl = 'https://myglamour.store/all-orders';
$response = file_get_contents($apiUrl);

if ($response === false) {
    echo 'Error fetching orders.';
} else {
    // Decode the JSON response
    $orders = json_decode($response, true);

    // Check if orders are fetched successfully
    if (!$orders) {
        echo 'No orders found.';
    } else {
        // Display a message to indicate the page is running
        echo "Page is running.";

        // Loop through each order
        foreach ($orders as $order) {
            // Check if 'third_party_delivery_tracking_id' is not null
            if (!empty($order['third_party_delivery_shipment_id'])) {
                // Output JavaScript asynchronously for each order
                ?>
                <script>
                    // async function track_shipRocket_shipment(orderId) {
                    //     try {
                    //         // Step 1: Login API call
                    //         const loginEndpoint = 'https://apiv2.shiprocket.in/v1/external/auth/login';
                    //         const loginResponse = await fetch(loginEndpoint, {
                    //             method: 'POST',
                    //             headers: {
                    //                 'Content-Type': 'application/json',
                    //             },
                    //             body: JSON.stringify({
                    //                 'email': 'admin1@myglamour.store',
                    //                 'password': 'cGUNwXnDTZCnQuw',
                    //             }),
                    //         });

                    //         if (!loginResponse.ok) {
                    //             throw new Error('Login failed');
                    //         }

                    //         const loginData = await loginResponse.json();
                    //         const token = loginData.token;

                    //         // Step 2: Track Shipment API call
                    //         const trackEndpoint = 'https://apiv2.shiprocket.in/v1/external/courier/track/shipment/' + orderId;
                    //         const trackResponse = await fetch(trackEndpoint, {
                    //             method: 'GET',
                    //             headers: {
                    //                 'Content-Type': 'application/json',
                    //                 'Authorization': 'Bearer ' + token,
                    //             },
                    //         });

                    //         // Check response status
                    //         if (![200, 202].includes(trackResponse.status)) {
                    //             throw new Error('Track Shipment failed with status code: ' + trackResponse.status);
                    //         }

                    //         const trackData = await trackResponse.json();

                    //         // Check track_status
                    //         if (trackData.tracking_data.track_status !== 1) {
                    //             throw new Error('Invalid track_status: ' + trackData.tracking_data.track_status);
                    //         }

                    //         const currentStatus = trackData.tracking_data.shipment_track[0].current_status;

                    //         // Step 3: Update Delivery Status based on current_status
                    //         switch (currentStatus) {
                    //             case 'PICKED UP':
                    //                 changeDeliveryStatus('intransit');
                    //                 break;
                    //             case 'OUT FOR DELIVERY':
                    //                 changeDeliveryStatus('out_for_delivery');
                    //                 break;
                    //             case 'DELIVERED':
                    //                 changeDeliveryStatus('delivered');
                    //                 break;
                    //             case 'CANCELED':
                    //                 changeDeliveryStatus('canceled');
                    //                 break;
                    //             case 'Return Delivered':
                    //                 changeDeliveryStatus('returned');
                    //                 break;
                    //             case 'Undelivered':
                    //                 changeDeliveryStatus('failed');
                    //                 break;
                    //             default:
                    //                 // Handle other statuses if needed
                    //                 break;
                    //         }

                    //     } catch (error) {
                    //         console.error('Error:', error.message);
                    //         // Display toast with error message
                    //         // Implement your toast logic here
                    //         //toastr.error('Error: ' + error.message);
                    //     }
                    // }
                    async function track_shipRocket_shipment() {
        try {
            // Step 1: Login API call
            //const loginEndpoint = 'https://apiv2.shiprocket.in/v1/external/auth/login';
            const proxyEndpoint = 'https://myglamour.store/proxy.php';
            const loginResponse = await fetch(proxyEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'login',
                    data: {
                        email: "{{ config('shiprocket.user_id') }}",
                        password: "{{ config('shiprocket.password') }}",
                    }
                }),
            });

            if (!loginResponse.ok) {
                throw new Error('Login failed');
            }

            const loginData = await loginResponse.json();
            const token = loginData.token;
            //console.log("login successful");
            // Step 2: Track Shipment API call
            const orderId = '{{$order->third_party_delivery_shipment_id}}'; // Replace with the actual order ID
            //const trackEndpoint = `https://apiv2.shiprocket.in/v1/external/courier/track/shipment/${orderId}`;
            
            //const proxyEndpoint = 'https://myglamour.store/proxy.php';
            const trackEndpoint = `https://myglamour.store/proxy1.php?action=trackShipment&token=${token}&shipmentId=${orderId}`;

            const trackResponse = await fetch(trackEndpoint, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                
            });

            // Check response status
            if (![200, 202].includes(trackResponse.status)) {
                throw new Error(`Track Shipment failed with status code: ${trackResponse.status}`);
            }

            const trackData = await trackResponse.json();
            
           // console.log("tarck data is",trackData);  // Log the response for debugging
            // Check track_status
            if (trackData.tracking_data.track_status !== 1) {
                throw new Error(`Invalid track_status: ${trackData.tracking_data.track_status}`);
            }

            const currentStatus = trackData.tracking_data.shipment_track[0].current_status;

            // Step 3: Update Delivery Status based on current_status
            switch (currentStatus) {
                case 'PICKED UP':
                    changeDeliveryStatus('intransit');
                    break;
                case 'OUT FOR DELIVERY':
                    changeDeliveryStatus('out_for_delivery');
                    break;
                case 'DELIVERED':
                    changeDeliveryStatus('delivered');
                    break;
                // case 'CANCELED':
                //     changeDeliveryStatus('canceled');
                //     break;
                case 'Return Delivered':
                    changeDeliveryStatus('returned');
                    break;
                case 'Undelivered':
                    changeDeliveryStatus('failed');
                default:
                    // Handle other statuses if needed
                    break;
            }

        } catch (error) {
            console.error('Error:', error.message);
            // Display toast with error message
            // Implement your toast logic here
            //toastr.error(`Error: ${error.message}`);
        }
    }

                    function changeDeliveryStatus(status) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "{{route('admin.orders.status')}}",
                            method: 'POST',
                            data: {
                                "id": '<?php echo $order['id']; ?>',
                                "order_status": status
                            },
                            success: function (data) {
                                if (data.success == 0) {
                                    //toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}} !!');
                                    location.reload();
                                } else {
                                    if (data.payment_status == 0) {
                                        //toastr.warning('{{translate("before_delivered_you_need_to_make_payment_status_paid")}}!');
                                        location.reload();
                                    } else if (data.customer_status == 0) {
                                        //toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
                                        location.reload();
                                    } else {
                                        //toastr.success('{{translate("status_change_successfully")}}!');
                                        location.reload();
                                    }
                                }
                            }
                        });
                    }

                    // Call the JavaScript function asynchronously for each order
                    track_shipRocket_shipment('<?php echo $order['third_party_delivery_shipment_id']; ?>');
                </script>
                <?php
            }
        }
    }
}
?>
