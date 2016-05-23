<html>
    <head>
        <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="{{URL::to('/')}}/images/ciete_logo.ico">
        <link rel="stylesheet" href="{{URL::to('/')}}/css/style.css">
        <link rel="stylesheet" href="{{URL::to('/')}}/css/AdminLTE.min.css">
        <link rel="stylesheet" href="{{URL::to('/')}}/css/bootstrap.css">
        <link rel="stylesheet" href="{{URL::to('/')}}/css/bootstrap-3.3.5.css">
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="centro">
                    <img src="{{URL::to('/')}}/images/ciete.png" class="img-responsive insta" style="">
                    <div class="row">
                        <h4><strong>Whoops!</strong>Ha ocurrido un error</h4>
                        <h2 style="display: none">{{$error}}</h2>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
