<!DOCTYPE html>
<html>
    <head>
        <title>Paypal Integration</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container" style="width:30%;border:1px solid #ddd;position:fixed;top:25%;left:35%;">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {!! $message !!}
                </div>
            <?php Session::forget('success');?>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    {!! $message !!}
                </div>
            <?php Session::forget('error');?>
            @endif

            <div style="background:#040482b0;padding:1px;margin-left:-15px;margin-right:-15px;">
                <h2 style="text-align:center;color:#fff">Paywith Paypal</h2>
            </div>
            <br>
            <form class="form-horizontal" method="POST" action="{!! URL::to('paypal') !!}">
                {{ csrf_field() }}
                <p>Demo Paypal Form - Integrating Paypal In Laravel</p><br>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="amount">Enter Amount:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="amount" placeholder="Enter Amount" name="amount">
                    </div>
                </div><br>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10" style="text-align:center">
                        <button type="submit" class="btn btn-success">Pay with PayPal</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
