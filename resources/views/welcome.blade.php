<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .rcorners1 {
                color: white;
                border-radius: 25px;
                background: #374f67;
                padding: 10px; 
                width: 200px;
                height: 150px;
                margin-bottom: 8px;  
            }
        </style>
    </head>
    <body>
        <h1 style="text-align:center">Wifi Statusdaten für das Objekt mit der ID: <?php echo $objectId ?></h1>
        <p  style="text-align:center">Alle Zahlen beziehen sich immer auf die letzten 30 Tage.</p>
        <div class="flex-center position-ref"> 
            <div class="content"> 
                <ul style="float:left">
                    <div class="rcorners1">
                        <p>Seitenaufrufe</p>
                        <h1><?php echo $sessions ?></h1>
                    </div>
                    <div class="rcorners1">
                        <p>Nutzer</p>
                        <h1><?php echo $users ?></h1>
                    </div>
                </ul>

            <div id="os-chart" style="float:right"></div>
        </div>
    </body>
</html>                
                

