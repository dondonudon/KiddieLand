<!DOCTYPE html>
<html lang="id">
<head>
    <title>Gopay</title>
</head>
<body>
<div id="response"></div>

<script src="{{ asset('vendors/general.js') }}"></script>
<script type="text/javascript">
    const view = document.getElementById('response');

    jQuery.support.cors = true;
    let settings = {
        "url": "https://api.sandbox.midtrans.com/v2/charge",
        "method": "POST",
        "timeout": 0,
        "headers": {
            'X-Requested-With':  'XMLHttpRequest',
            "Authorization": "Basic U0ItTWlkLXNlcnZlci1TNEZ4dXY0RkdFRlZtZUZJTlZpYVQ0VG46",
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        "data": JSON.stringify({"payment_type":"gopay","transaction_details":{"order_id":"order-101","gross_amount":44000}}),
    };

    $.ajax(settings).done(function (response) {
        console.log(response);
    });
</script>
</body>
</html>