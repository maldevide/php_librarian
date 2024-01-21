<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="description" content="Stacks - The Maldevide Studios Knowledge Base" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="manifest" href="/manifest.json" crossorigin='use-credentials' />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="theme-color" content="white" />
<script>
  if ("serviceWorker" in navigator) { navigator.serviceWorker.register("/worker.js") }
</script>
    <title><?=$title?></title>
    <style>
        .main {
            margin: 0 auto;
            width: 90%;
            padding: 16px;
            background-color: #eee;
        }
        .rounder {
            /* rounded corners */
            border-radius: 12px;
            /* subtle shadow */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .caret {
            cursor: pointer;
            user-select: none; /* Prevent text selection */
        }
        .p1 {
            padding: 8px;
        }
        .p2 {
            padding: 16px;
        }
        .m1 {
            margin: 8px;
        }
        .m2 {
            margin: 16px;
        }

        .caret::before {
            content: "\25B6"; /* Black right-pointing triangle */
            color: black;
            display: inline-block;
            margin-right: 6px;
        }

        .caret-down::before {
            transform: rotate(90deg); /* Rotate the caret */
        }

        .nested {
            display: none;
            list-style-type: none; /* Remove bullet points from nested lists */
        }

        .document-info {
            display: flex;
            flex-wrap: wrap;
            margin-top: 16px;
            margin-bottom: 16px;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .document-info > div {
            min-width: 150px;
            margin-right: 15px;
            margin-bottom: 10px;
        }

        .document-grid {
            display: grid;
            /* Additional grid styling */
        }

        .full {
            grid-column: 1 / -1; /* Span full width */
            /*flex-basis: 100%;*/
        }

        .full span {
            font-weight: bold;
        }

        ul {
            list-style-type: none;
            margin: 0;
            margin-top: 10px;
            padding: 0;
            padding-left: 20px;
        }
        ul li {
            margin-bottom: 10px;
        }
        .flexbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f4f4f4; /* Light grey background */
            border: 1px solid #ccc; /* Light grey border */
            border-radius: 10px; /* Rounded corners */
            padding: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .flexbar a {
            text-decoration: none;
            color: #336699; /* Scholarly blue */
            padding: 5px 10px;
            margin: 0 5px;
            border-radius: 5px; /* Slightly rounded corners for links */
            transition: background-color 0.3s;
        }

        .flexbar a:hover {
            background-color: #e8e8e8; /* Light grey background on hover */
        }

        .flexbar span {
            font-weight: bold;
            color: #444; /* Dark grey color for text */
        }
        .forebox {
            background-color: #fff;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="main rounder">
    <h1 class="m1 p1"><?=$title?></h1>
    <hr/>
