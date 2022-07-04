<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HeatMap - Challenge</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <style>
        .content { 
            padding-right: 50px !important;
            padding-left: 50px !important;
            margin-right: auto;
            margin-left: auto;
        }
        .jumbotron {
            background-color: white !important;
        }
        .display-4 {
            color: rgb(61, 61, 61) !important;
        }
        .canvas {
            position: relative;
            background-color: beige;
        }

        .item {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0.2px solid #fbfbfb;
        }
        .filter { 
            margin-bottom: 20px;
        }
        .legend { list-style: none; margin-top: 30px; margin-bottom: 100px; padding-left: 0px;}
        .legend li { float: left; margin-right: 20px; font-size:14px; }
        .legend .lead { font-size:16px; }
        .legend span { float: left; width: 100%; height: 12px; margin: 2px; }
        /* your colors */
        .legend .s1 { background-color: #FE9D52; }
        .legend .s2 { background-color: #FFCEA9; }
        .legend .s3 { background-color: #9ECBED; }
        .legend .s4 { background-color: #3C97DA; }
        .legend .s5 { background-color: #2A6A99; }

    </style>
    
    @livewireStyles
</head>
<body>

    @livewire('heatmap-index')

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

</body>
</html>