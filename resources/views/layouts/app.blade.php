<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lumtify blog">
    <meta name="keywords" content="academy, course, education, education html theme, elearning, learning,">
    <meta name="author" content="Peter Lai">

    <title>Lumtify</title>

    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet" type="text/css">
    <style>
    html {
        height: 100%;
    }
    #pre-loader {
        width: 100%;
    }
    #pre-loader-item {
        text-align: center;
        margin-top: 10%;
    }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="pre-loader">
        <div id="pre-loader-item">
          <div>
            <p>@lang('info.loading')</p>
          </div>
        </div>
    </div>
    <v-app id="app" top-navbar>
        <page-lumtify v-bind:auth="auth"></page-lumtify>
    </v-app>
    <script src="{{ env('APP_URL') }}/js/app.js" async defer></script>
</body>
</html>