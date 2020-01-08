<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Frosty</title>
  <style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  html, body {
    width: 100%;
    min-height: 100%;
  }

  .content {
    padding: 2rem;
  }

  table {
    width: 100%;
    border: 1px solid #000;
  }

  td {
    border-top: 1px solid #000;
    border-right: 1px solid #000;
    text-align: center;
    padding: .3rem 0;
  }

  ul {
    margin-left: 2rem;
  }

  .menu {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
  }

  .menu li {
    float: left;
  }

  .menu li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
  }

  .menu li a:hover:not(.active) {
    background-color: #111;
  }

  .menu .active {
    background-color: #4CAF50;
  }

  .panel {
    display: flex;
    width: calc(100% - 20rem);
    margin: 10rem;
    padding: 5rem;
    border-radius: .5rem;
    background: #ededed;
    border: 1px solid #ddd;
    flex-direction: column;
    align-items: center;
  }
  </style>
</head>

<body>
