<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create Event</title>
        <style>
            .createEventH1 {
    text-align: center;
    margin-top: 80px;
    margin-bottom: 40px;
}
.forms{
    display: flex;
    align-items: stretch;
}

.form1 {
    flex: 0 0 70%; /* Flex-grow, flex-shrink, flex-basis */
    margin-right: 5%; /* Add space between forms */
}

.form2 {
    flex: 0 0 25%;
}

.form1 {
    margin: auto;
    background-color: #f9f9f9;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
    width: 80%;
}

.row1,
.row2,
.row3 {
    display: flex;
    flex-wrap: wrap; /* Allow flex items to wrap to the next line */
    margin-bottom: 20px;
}

.col1,
.col2,
.col3 {
    flex: 1;
    margin-right: 20px;
    margin-bottom: 20px; /* Add some space between columns and rows */
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
select,
textarea,
input[type="date"],
input[type="time"] {
    width: calc(100% - 22px); /* Subtracting the border width */
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    margin-bottom: 10px;
}

textarea {
    height: 100px;
}
.form2 {
    margin: 20px auto;
    background-color: #f9f9f9;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
    max-width: 400px; /* Adjust the width as needed */
}

.form2 h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #333;
}

.form2 p {
    margin-bottom: 10px;
}

.form2 img {
    width: 100%;
    border-radius: 5px;
    margin-bottom: 10px;
}

.form2 hr {
    border: none;
    border-top: 1px solid #ccc;
    margin: 20px 0;
}

.form2 button {
    display: block;
    background-color: orange;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form2 button:hover {
    background-color: #ff8c00;
}



        </style>
    </head>
    <body>
    <?php $activePage = 'createEvents'; include '../general/header.php'?>
        
    <h1 class="createEventH1">Create Events</h1><hr>
    <div class="forms">
    <div class="form1">
            <form action="" method="POST">
                <div class="row1">
                    <div class="col1">
                        <label for="name1">Event Name</label>
                        <input type="text" id="name1" value="" placeholder="Event Name *"/>
                    </div>
                    <div class="col2">
                        <label for="hall">Hall Selection</label>
                        <select id="hall" name="hall" >
                            <option value="hall1">Hall 1</option>
                            <option value="hall2">Hall 2</option>
                            <option value="hall3">Hall 3</option>
                        </select>
                    </div>
                </div>
                <div class="row2">
                    <div class="col1">
                        <label for="ename">Event Description</label>
                        <textarea id="eDetails" rows="7" cols="60"></textarea>
                    </div> 
                    <div class="col2">
                        <label for="region">Date Start</label>
                        <input type="date" id="date" style="width: 120px;"/> 
                    </div>
                    <div class="col3">
                        <label for="region">Date End</label>
                        <input type="date" id="date" style="width: 120px;"/> 
                    </div>
                </div>
                <div class="row3">
                    <div class="col1">
                        <label for="ename">Time Start</label>
                        <input type="time" id="date" style="width: 120px;"/> 
                    </div> 
                    <div class="col2">
                        <label for="region">Time End</label>
                        <input type="time" id="date" style="width: 120px;"/> 
                    </div>
                </div>
                <div>
                    <label for="">Each seat price (<b>RM</b>) : </label><input type="text" name="" id="">
                </div>
                
        </div>
        <div class="form2">
    <h3>Hall 1</h3>
    <p>Hall 1 Description ajsahdjahjdhajshdjasd</p>
    <img src="img/hall1.jpg" alt="">
    <hr>
    <p>People : <b>40</b></p>
    <p>Price to book : <b>RM 3000.00</b> per day</p>
    <button class="btn-proceed">Proceed to payment</button>
</div>

    </div>
    
    </div>
        <?php include '../general/footer.php'?>
    </body>
</html>
