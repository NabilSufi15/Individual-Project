<!doctype html>
<meta charset="utf-8">
<head>
  <link href="CSS/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script src="pixi/pixi.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php
//  starts the session
session_start();
// If the user is not signed in send back to home page
if (!isset($_SESSION['username'])) {
  header("Location:home.html");

} else {
}
?>

<header>
  <h1>
    <center> <img src="assets/WebsiteName.png"/> </center>
  </h1>
</header>
<!--  Game will be displayed -->
<canvas id="game"></canvas>

<!-- displays global navigational bar -->
<div id="navBar">
  <ul>
    <li><a href= "userHomepage.php"><b>Home</b></a></li>
    <li><a href= "gameResults.php"><b>Results</b></a></li>
    <li><a href= "logout.php"><b>LogOut</b></a></li>
  </ul>
</div>

<!-- Retrieves data of the game and sends it to resultsProcessing.php -->
<form method="post" action="resultsProcessing.php">
  <div>
    <div>
      <input type="hidden" id="hiddenScore" name= "score" value="0">
      <input type="hidden" id="hiddenAttempt" name= "attempt" value="0">
      <input type="hidden" id="hiddenName" name= "game">
      <input type="hidden" id="hiddenID" name= "game_id">
      <input type="hidden" id="hiddenTemplate" name= "template">
      <input type="hidden" id="hiddenRating" name= "rating" value = 0>
      <button type="submit" name="submit" class="btn2"> Submit Score </button>
    </div>
  </div>
</form>

<script>
$(document).ready(function() {

    //Aliases
    let Application = PIXI.Application,
        Container = PIXI.Container,
        loader = PIXI.loader,
        resources = PIXI.loader.resources,
        Graphics = PIXI.Graphics,
        TextureCache = PIXI.utils.TextureCache,
        Sprite = PIXI.Sprite.fromImage;
        Text = PIXI.Text,
        TextStyle = PIXI.TextStyle;

    //Creates the canvas of the game
    var canvas = document.getElementById('game');
    let app = new Application({
        width: 960,
        height: 625,
        view: canvas
    });
    document.body.appendChild(app.view);

    //Gets the image from the asset folder and adds it to the background
    var background = PIXI.Sprite.fromImage('/FYP/assets/background.png');
    background.width = app.screen.width;
    background.height = app.screen.height;
    app.stage.addChild(background);

    //Textures for the check button
    var textureButton = PIXI.Texture.fromImage('/FYP/assets/check.jpg');
    var textureButtonDown = PIXI.Texture.fromImage('/FYP/assets/check2.jpg');
    var textureButtonOver = PIXI.Texture.fromImage('/FYP/assets/check2.jpg');

    //Textures for the rating5 button
    var rating5Texture = PIXI.Texture.fromImage('/FYP/assets/rating5.jpg');
    var rating5TextDown = PIXI.Texture.fromImage('/FYP/assets/rating5Over.jpg');
    var rating5Textover = PIXI.Texture.fromImage('/FYP/assets/rating5Over.jpg');

    //Textures for the rating4 button
    var rating4Texture = PIXI.Texture.fromImage('/FYP/assets/rating4.jpg');
    var rating4TextDown = PIXI.Texture.fromImage('/FYP/assets/rating4Over.jpg');
    var rating4Textover = PIXI.Texture.fromImage('/FYP/assets/rating4Over.jpg');

    //Textures for the rating3 button
    var rating3Texture = PIXI.Texture.fromImage('/FYP/assets/rating3.jpg');
    var rating3TextDown = PIXI.Texture.fromImage('/FYP/assets/rating3Over.jpg');
    var rating3Textover = PIXI.Texture.fromImage('/FYP/assets/rating3Over.jpg');

    //Textures for the rating2 button
    var rating2Texture = PIXI.Texture.fromImage('/FYP/assets/rating2.jpg');
    var rating2TextDown = PIXI.Texture.fromImage('/FYP/assets/rating2Over.jpg');
    var rating2Textover = PIXI.Texture.fromImage('/FYP/assets/rating2Over.jpg');

    //Textures for the rating1 button
    var rating1Texture = PIXI.Texture.fromImage('/FYP/assets/rating1.jpg');
    var rating1TextDown = PIXI.Texture.fromImage('/FYP/assets/rating1Over.jpg');
    var rating1Textover = PIXI.Texture.fromImage('/FYP/assets/rating1Over.jpg');

    //initialising the variables
    var choiceSelection = new Array();
    var questionNumber = 0;
    var scores;
    var score = 0;
    var attempt = 0;
    var message;
    let image, box, box2, box3;
    var q, c1, c2, c3, text1, text2, checkButton;
    var correctSound = new Audio('sound/correct.wav');
    var wrongSound = new Audio('sound/wrong.wav');

    $.getJSON('template.json', function(json) {

        if (json[5].data) {
            //gets the game name, template, id and stores into hidden variable
            hiddenName.value = json[5].game_name;
            hiddenTemplate.value = json[5].game_template;
            hiddenID.value = json[5].game_id;

            // loops through the json template file and retrieves the data
            for (i = 0; i < json[5].data.length; i++) {
              choiceSelection[i] = new Array;
              choiceSelection[i][0] = json[5].data[i].question;
              choiceSelection[i][1] = json[5].data[i].text1;
              choiceSelection[i][2] = json[5].data[i].text2;
              choiceSelection[i][3] = json[5].data[i].image1;
              choiceSelection[i][4] = json[5].data[i].image2;
              choiceSelection[i][5] = json[5].data[i].image3;
            }

            displayQuestion();
            console.log(json[5].data)
        }



    })

    //displays game data on the screen
    function displayQuestion() {

        q = choiceSelection[questionNumber][0];
        c1 = choiceSelection[questionNumber][3];
        c2 = choiceSelection[questionNumber][4];
        c3 = choiceSelection[questionNumber][5];
        c4 = choiceSelection[questionNumber][1];
        c5 = choiceSelection[questionNumber][2];

        //create box and displays it
        box = new Graphics();
        box.lineStyle(4, 0x000000, 1);
        box.beginFill(0x66CCFF);
        box.drawRect(0, 0, 150, 150);
        box.endFill();
        box.x = 200;
        box.y = 380;
        app.stage.addChild(box);

        //create box and displays it
        box2 = new Graphics();
        box2.lineStyle(4, 0x000000, 1);
        box2.beginFill(0x66CCFF);
        box2.drawRect(0, 0, 150, 150);
        box2.endFill();
        box2.x = 400;
        box2.y = 380;
        app.stage.addChild(box2);

        //create box and displays it
        box3 = new Graphics();
        box3.lineStyle(4, 0x000000, 1);
        box3.beginFill(0x66CCFF);
        box3.drawRect(0, 0, 150, 150);
        box3.endFill();
        box3.x = 600;
        box3.y = 380;
        app.stage.addChild(box3);

        //create pool and displays it
        pool = new Graphics();
        pool.lineStyle(4, 0x000000, 1);
        pool.beginFill(0x66CCFF);
        pool.drawRect(0, 0, 600, 150);
        pool.endFill();
        pool.x = 150;
        pool.y = 150;
        app.stage.addChild(pool);

        // source: http://flashbynight.com/tutes/pixquiz/
        // Gives a random number between 1 and 3
        // rnd will decide the positoins of the choice selections
        var rnd = Math.random() * 3;
        rnd = Math.ceil(rnd);
        q = choiceSelection[questionNumber][0];

        // if rnd is 1, position of the choice boxes will be c1,c2,c3
        if (rnd == 1) {

          //Stores the image1 from the template json file and displays it
          image = new Sprite(c1);
          image.x = 275;
          image.y = 226;
          image.width = 100;
          image.height = 100;
          image.buttonMode = true;
          image.anchor.set(0.5);
          image.interactive = true;

          image
              .on('pointerdown', onDragStart)
              .on('pointerup', onDragEnd)
              .on('pointerupoutside', onDragEnd)
              .on('pointermove', onDragMove);

          app.stage.addChild(image);

          //Stores the image2 from the template json file and displays it
          image2 = new Sprite(c2);
          image2.x = 475;
          image2.y = 226;
          image2.width = 100;
          image2.height = 100;
          image2.buttonMode = true;
          image2.anchor.set(0.5);
          image2.interactive = true;

          image2
              .on('pointerdown', onDragStart)
              .on('pointerup', onDragEnd)
              .on('pointerupoutside', onDragEnd)
              .on('pointermove', onDragMove);

          app.stage.addChild(image2);

          //Stores the image3 from the template json file and displays it
          image3 = new Sprite(c3);
          image3.x = 675;
          image3.y = 226;
          image3.width = 100;
          image3.height = 100;
          image3.buttonMode = true;
          image3.anchor.set(0.5);
          image3.interactive = true

          image3
              .on('pointerdown', onDragStart)
              .on('pointerup', onDragEnd)
              .on('pointerupoutside', onDragEnd)
              .on('pointermove', onDragMove);

          app.stage.addChild(image3);
        }

        // if rnd is 2, position of the choice boxes will be c2,c3,c1
        if (rnd == 2) {

          //Stores the image1 from the template json file and displays it
          image2 = new Sprite(c2);
          image2.x = 275;
          image2.y = 226;
          image2.width = 100;
          image2.height = 100;
          image2.buttonMode = true;
          image2.anchor.set(0.5);
          image2.interactive = true;

          image2
              .on('pointerdown', onDragStart)
              .on('pointerup', onDragEnd)
              .on('pointerupoutside', onDragEnd)
              .on('pointermove', onDragMove);

          app.stage.addChild(image2);

          //Stores the image3 from the template json file and displays it
          image3 = new Sprite(c3);
          image3.x = 475;
          image3.y = 226;
          image3.width = 100;
          image3.height = 100;
          image3.buttonMode = true;
          image3.anchor.set(0.5);
          image3.interactive = true;

          image3
              .on('pointerdown', onDragStart)
              .on('pointerup', onDragEnd)
              .on('pointerupoutside', onDragEnd)
              .on('pointermove', onDragMove);

          app.stage.addChild(image3);

          //Stores the image from the template json file and displays it
          image = new Sprite(c1);
          image.x = 675;
          image.y = 226;
          image.width = 100;
          image.height = 100;
          image.buttonMode = true;
          image.anchor.set(0.5);
          image.interactive = true;

          image
              .on('pointerdown', onDragStart)
              .on('pointerup', onDragEnd)
              .on('pointerupoutside', onDragEnd)
              .on('pointermove', onDragMove);

          app.stage.addChild(image);

        }

        // if rnd is 3, position of the choice boxes will be c3,c1,c2
        if (rnd == 3) {

          //Stores the image3 from the template json file and displays it
          image3 = new Sprite(c3);
          image3.x = 275;
          image3.y = 226;
          image3.width = 100;
          image3.height = 100;
          image3.buttonMode = true;
          image3.anchor.set(0.5);
          image3.interactive = true;

          image3
              .on('pointerdown', onDragStart)
              .on('pointerup', onDragEnd)
              .on('pointerupoutside', onDragEnd)
              .on('pointermove', onDragMove);

          app.stage.addChild(image3);

          //Stores the image from the template json file and displays it
          image = new Sprite(c1);
          image.x = 475;
          image.y = 226;
          image.width = 100;
          image.height = 100;
          image.buttonMode = true;
          image.anchor.set(0.5);
          image.interactive = true;

          image
              .on('pointerdown', onDragStart)
              .on('pointerup', onDragEnd)
              .on('pointerupoutside', onDragEnd)
              .on('pointermove', onDragMove);

          app.stage.addChild(image);

          //Stores the image2 from the template json file and displays it
          image2 = new Sprite(c2);
          image2.x = 675;
          image2.y = 226;
          image2.width = 100;
          image2.height = 100;
          image2.buttonMode = true;
          image2.anchor.set(0.5);
          image2.interactive = true;

          image2
              .on('pointerdown', onDragStart)
              .on('pointerup', onDragEnd)
              .on('pointerupoutside', onDragEnd)
              .on('pointermove', onDragMove);

          app.stage.addChild(image2);

        }

        // the designs of the fonts
        let style = new TextStyle({fontFamily: "sans-serif", fontSize: 40, fill: "blue", fontWeight: "bolder"});
        let style2 = new TextStyle({fontFamily: "sans-serif", fontSize: 80, fill: "black", fontWeight: "bolder"});

        //displays word1 from the template json file
        text1 = new Text(c4, style);
        text1.position.set(120, 450);
        text1.anchor.set(0.5, 0.5);
        app.stage.addChild(text1);

        //displays the word2 from the template json file
        text2 = new Text(c5, style);
        text2.position.set(820, 450);
        text2.anchor.set(0.5, 0.5);
        app.stage.addChild(text2);

        //displays the score and attempt on the game screen
        scores = new Text(score + "/" + attempt, style);
        scores.position.set(900, 30);
        scores.anchor.set(0.5, 0.5);
        app.stage.addChild(scores);

        //displays the question from the template json file
        question = new Text(q, style);
        question.position.set(450, 100);
        question.anchor.set(0.5, 0.5);
        app.stage.addChild(question);

        //Sets the position of the message
        message = new Text("", style2);
        message.position.set(500, 575);
        message.anchor.set(0.5, 0.5);
        app.stage.addChild(message);

        //displays the check button and makes it interactive
        checkButton = new PIXI.Sprite(textureButton);
        checkButton.buttonMode = true;
        checkButton.anchor.set(0.5);
        checkButton.width = 100;
        checkButton.height = 50;
        checkButton.x = 850;
        checkButton.y = 575;
        checkButton.interactive = true;
        checkButton.buttonMode = true;

        checkButton
            // Mouse & touch events are normalized into
            // the pointer* events for handling different
            // button events.
            .on('pointerdown', onButtonDown)
            .on('pointerup', onButtonUp)
            .on('pointerupoutside', onButtonUp)
            .on('pointerover', onButtonOver)
            .on('pointerout', onButtonOut);

        // displays the button to the stage
        app.stage.addChild(checkButton);

    }

    //called when there is no questions left within the template json file
    function finish() {

        //the font designs
        let style = new TextStyle({
            fontFamily: "sans-serif",
            fontSize: 60,
            fill: "black",
            fontWeight: "bolder"
        });
        let style2 = new TextStyle({
            fontFamily: "sans-serif",
            fontSize: 30,
            fill: "blue",
            fontWeight: "bolder"
        });

        //displays the score and attempts
        var done = new Text("Score: " + score + " Attempts: " + attempt, style);
        done.position.set(450, 475);
        done.anchor.set(0.5, 0.5);
        app.stage.addChild(done);

        //dsplays the text on the stage
        var rate = new Text("Please rate our game and press the submit button above", style2);
        rate.position.set(450, 90);
        rate.anchor.set(0.5, 0.5);
        app.stage.addChild(rate);

        //displays 1 on the stage
        var num1 = new Text("1", style);
        num1.position.set(200, 275);
        num1.anchor.set(0.5, 0.5);
        app.stage.addChild(num1);

        //displays 2 on the stage
        var num2 = new Text("2", style);
        num2.position.set(325, 275);
        num2.anchor.set(0.5, 0.5);
        app.stage.addChild(num2);

        //displays 3 on the stage
        var num3 = new Text("3", style);
        num3.position.set(450, 275);
        num3.anchor.set(0.5, 0.5);
        app.stage.addChild(num3);

        //displays 4 on the stage
        var num4 = new Text("4", style);
        num4.position.set(575, 275);
        num4.anchor.set(0.5, 0.5);
        app.stage.addChild(num4);

        //displays 5 on the stage
        var num5 = new Text("5", style);
        num5.position.set(700, 275);
        num5.anchor.set(0.5, 0.5);
        app.stage.addChild(num5);

        // displays the ratingOne image on the stage
        ratingOneButton = new PIXI.Sprite(rating1Texture);
        ratingOneButton.buttonMode = true;
        ratingOneButton.anchor.set(0.5);
        ratingOneButton.width = 100;
        ratingOneButton.height = 100;
        ratingOneButton.x = 200;
        ratingOneButton.y = 200;
        ratingOneButton.interactive = true;
        ratingOneButton.buttonMode = true;

        //calls the rating1 function
        ratingOneButton
            // Mouse & touch events are normalized into
            // the pointer* events for handling different
            // button events.
            .on('pointerdown', rating1);

        app.stage.addChild(ratingOneButton);

        //// displays the ratingTwo image on the stage
        ratingTwoButton = new PIXI.Sprite(rating2Texture);
        ratingTwoButton.buttonMode = true;
        ratingTwoButton.anchor.set(0.5);
        ratingTwoButton.width = 100;
        ratingTwoButton.height = 100;
        ratingTwoButton.x = 325;
        ratingTwoButton.y = 200;
        ratingTwoButton.interactive = true;
        ratingTwoButton.buttonMode = true;

        //calls the rating2 function
        ratingTwoButton
            // Mouse & touch events are normalized into
            // the pointer* events for handling different
            // button events.
            .on('pointerdown', rating2);

        app.stage.addChild(ratingTwoButton);

        // displays the ratingThree image on the stage
        ratingThreeButton = new PIXI.Sprite(rating3Texture);
        ratingThreeButton.buttonMode = true;
        ratingThreeButton.anchor.set(0.5);
        ratingThreeButton.width = 100;
        ratingThreeButton.height = 100;
        ratingThreeButton.x = 450;
        ratingThreeButton.y = 200;
        ratingThreeButton.interactive = true;
        ratingThreeButton.buttonMode = true;

        //calls the rating3 function
        ratingThreeButton
            // Mouse & touch events are normalized into
            // the pointer* events for handling different
            // button events.
            .on('pointerdown', rating3);

        app.stage.addChild(ratingThreeButton);

        // displays the ratingFour image on the stage
        ratingFourButton = new PIXI.Sprite(rating4Texture);
        ratingFourButton.buttonMode = true;
        ratingFourButton.anchor.set(0.5);
        ratingFourButton.width = 100;
        ratingFourButton.height = 100;
        ratingFourButton.x = 575;
        ratingFourButton.y = 200;
        ratingFourButton.interactive = true;
        ratingFourButton.buttonMode = true;

        //calls the rating4 function
        ratingFourButton
            // Mouse & touch events are normalized into
            // the pointer* events for handling different
            // button events.
            .on('pointerdown', rating4);

        app.stage.addChild(ratingFourButton);

        // displays the ratingFive image on the stage
        ratingFiveButton = new PIXI.Sprite(rating5Texture);
        ratingFiveButton.buttonMode = true;
        ratingFiveButton.anchor.set(0.5);
        ratingFiveButton.width = 100;
        ratingFiveButton.height = 100;
        ratingFiveButton.x = 700;
        ratingFiveButton.y = 200;
        ratingFiveButton.interactive = true;
        ratingFiveButton.buttonMode = true;

        //calls the rating5 function
        ratingFiveButton
            // Mouse & touch events are normalized into
            // the pointer* events for handling different
            // button events.
            .on('pointerdown', rating5);


        app.stage.addChild(ratingFiveButton);
    }

    //changes the hidden value to 1 and changes the image
    function rating1() {
        ratingOneButton.isdown = true;
        ratingTwoButton.isdown = false;
        ratingThreeButton.isdown = false;
        ratingFourButton.isdown = false;
        ratingFiveButton.isdown = false;

        // if down then change the textures of all the buttons
        // change hiddeRating value to 1
        if (ratingOneButton.isdown) {
            ratingOneButton.texture = rating1TextDown;
            ratingTwoButton.texture = rating2Texture;
            ratingThreeButton.texture = rating3Texture;
            ratingFourButton.texture = rating4Texture;
            ratingFiveButton.texture = rating5Texture;
            document.getElementById("hiddenRating").value = "1";

        }

    }

    //changes the hidden value to 2 and changes the image
    function rating2() {
        ratingTwoButton.isdown = true;
        ratingOneButton.isdown = false;;
        ratingThreeButton.isdown = false;
        ratingFourButton.isdown = false;
        ratingFiveButton.isdown = false;

        // if down then change the textures of all the buttons
        // change hiddeRating value to 2
        if (ratingTwoButton.isdown) {
            ratingTwoButton.texture = rating2TextDown;
            ratingOneButton.texture = rating1Texture;
            ratingThreeButton.texture = rating3Texture;
            ratingFourButton.texture = rating4Texture;
            ratingFiveButton.texture = rating5Texture;
            document.getElementById("hiddenRating").value = "2";

        }

    }

    //changes the hidden value to 3 and changes the image
    function rating3() {
        ratingThreeButton.isdown = true;
        ratingOneButton.isdown = false;;
        ratingTwoButton.isdown = false;
        ratingFourButton.isdown = false;
        ratingFiveButton.isdown = false;

        // if down then change the textures of all the buttons
        // change hiddeRating value to 3
        if (ratingThreeButton.isdown) {
            ratingThreeButton.texture = rating3TextDown;
            ratingOneButton.texture = rating1Texture;
            ratingTwoButton.texture = rating2Texture;
            ratingFourButton.texture = rating4Texture;
            ratingFiveButton.texture = rating5Texture;
            document.getElementById("hiddenRating").value = "3";

        }

    }

    //changes the hidden value to 4 and changes the image
    function rating4() {
        ratingFourButton.isdown = true;
        ratingOneButton.isdown = false;
        ratingTwoButton.isdown = false;
        ratingThreeButton.isdown = false;
        ratingFiveButton.isdown = false;

        // if down then change the textures of all the buttons
        // change hiddeRating value to 4
        if (ratingFourButton.isdown) {
            ratingFourButton.texture = rating4TextDown;
            ratingOneButton.texture = rating1Texture;
            ratingTwoButton.texture = rating2Texture;
            ratingThreeButton.texture = rating3Texture;
            ratingFiveButton.texture = rating5Texture;
            document.getElementById("hiddenRating").value = "4";

        }

    }

    //changes the hidden value to 5 and changes the image
    function rating5() {
        ratingFiveButton.isdown = true;
        ratingOneButton.isdown = false;
        ratingTwoButton.isdown = false;
        ratingThreeButton.isdown = false;
        ratingFourButton.isdown = false;

        // if down then change the textures of all the buttons
        // change hiddeRating value to 5
        if (ratingFiveButton.isdown) {
            ratingFiveButton.texture = rating5TextDown;
            ratingOneButton.texture = rating1Texture;
            ratingTwoButton.texture = rating2Texture;
            ratingThreeButton.texture = rating3Texture;
            ratingFourButton.texture = rating4Texture;
            document.getElementById("hiddenRating").value = "5";

        }

    }

    // source: http://scottmcdonnell.github.io/pixi-examples/index.html?s=demos&f=dragging.js&title=Dragging
    // tracks the movement of object
    function onDragStart(event) {
        // store a reference to the data
        // the reason for this is because of multitouch
        // we want to track the movement of this particular touch
        this.data = event.data;
        this.alpha = 0.5;
        this.dragging = true;
    }

    // source: http://scottmcdonnell.github.io/pixi-examples/index.html?s=demos&f=dragging.js&title=Dragging
    // Stop tracking movement of object
    function onDragEnd() {
        this.alpha = 1;
        this.dragging = false;
        // set the interaction data to null
        this.data = null;
    }

    // source: http://scottmcdonnell.github.io/pixi-examples/index.html?s=demos&f=dragging.js&title=Dragging
    // allows movement of object
    function onDragMove() {
        if (this.dragging) {
            var newPosition = this.data.getLocalPosition(this.parent);
            this.x = newPosition.x;
            this.y = newPosition.y;
        }
    }

    // source: http://www.html5gamedevs.com/topic/24408-collision-detection/
    // checks for a collision between 2 objects
    function checkCollision(a, b) {
        var ab = a.getBounds();
        var bb = b.getBounds();
        return ab.x + ab.width > bb.x && ab.x < bb.x + bb.width && ab.y + ab.height > bb.y && ab.y < bb.y + bb.height;
    }

    function onButtonDown() {
        var correct = true;
        this.isdown = true;
        this.texture = textureButtonDown;
        this.alpha = 1;
        //increments attempt
        ++attempt;
        incrementAt();

        //all images in correct box is correct
        if (checkCollision(image, box) && checkCollision(image2, box2) && checkCollision(image3, box3)) {
            correct = true;
            box.tint = 0x7FFF00; //green
            box2.tint = 0x7FFF00; //green
            box3.tint = 0x7FFF00; //green
            //alert("all right");

            //if all images not in the correct box, correct = false
            //all images go back to pool
        } else if (!checkCollision(image, box) && !checkCollision(image2, box2) && !checkCollision(image3, box3)) {
            //box.tint = 0x66CCFF
            correct = false;
            box.tint = 0xff0000;
            box2.tint = 0xff0000;
            box3.tint = 0xff0000;
            image.x = 200;
            image.y = 226;
            image2.x = 400;
            image2.y = 226;
            image3.x = 600;
            image3.y = 226;
            //alert("all wrong");

            //if image1 is in correct but images2 and images 3 isn't correct= false
            //image2, image3 go back to pool
        } else if (checkCollision(image, box) && !checkCollision(image2, box2) && !checkCollision(image3, box3)) {
            //box.tint = 0x66CCFF
            correct = false;
            box.tint = 0x7FFF00; //green
            box2.tint = 0xff0000; //red
            box3.tint = 0xff0000; //red
            image2.x = 400;
            image2.y = 226;
            image3.x = 600;
            image3.y = 226;
            //alert("box1 right");

            // if image1 and image 2 in correct box, but image 3 isn't, correct = false
            //image3 go back to pool
        } else if (checkCollision(image, box) && checkCollision(image2, box2) && !checkCollision(image3, box3)) {
            //box.tint = 0x66CCFF
            correct = false;
            box.tint = 0x7FFF00; //green
            box2.tint = 0x7FFF00; //green
            box3.tint = 0xff0000; //red
            image3.x = 600;
            image3.y = 226;
            //alert("box1 + 2 right");

            // if image1 and image 3 in correct box, but image 2 isn't, correct = false
            //image2 go back to pool
        } else if (checkCollision(image, box) && checkCollision(image3, box3) && !checkCollision(image2, box2)) {
            //box.tint = 0x66CCFF
            correct = false;
            box.tint = 0x7FFF00; //green
            box3.tint = 0x7FFF00; //green
            box2.tint = 0xff0000; //red
            image2.x = 400;
            image2.y = 226;
            //alert("box1 + 3 right");

            // if image2 is in correct box but images and images 3 isn't correct= false
            //image and image3 go back to pool
        } else if (checkCollision(image2, box2) && !checkCollision(image3, box3) && !checkCollision(image, box)) {
            correct = false;
            box2.tint = 0x7FFF00; //green
            box.tint = 0xff0000; //red
            box3.tint = 0xff0000; //red
            image.x = 200;
            image.y = 226;
            image3.x = 600;
            image3.y = 226;
            //alert("box2 right");

            // if image2 and image3 is in correct box but image1 isn't correct= false
            //image go back to pool
        } else if (checkCollision(image2, box2) && checkCollision(image3, box3) && !checkCollision(image, box)) {
            correct = false;
            box2.tint = 0x7FFF00; //green
            box.tint = 0xff0000; //red
            box3.tint = 0x7FFF00; //green
            image.x = 200;
            image.y = 226;
            //alert("box2 + 3 right");

            // if image3 is in correct box but image1 and image2 isn't correct= false
            // image, image2 go back to pool
        } else if (checkCollision(image3, box3) && !checkCollision(image, box) && !checkCollision(image2, box2)) {
            correct = false;
            box3.tint = 0x7FFF00; //green
            box.tint = 0xff0000; //red
            box2.tint = 0xff00000; //red
            image.x = 200;
            image.y = 226;
            image2.x = 400;
            image2.y = 226;
            //alert("box3 right");

        } else {
            correct = false;
            alert("wrong");
        }

        //if all questions correct
        if (correct) {

            //increments score
            scores.text = ++score + "/" + attempt;
            incrementSc();

            //play correct sound file
            correctSound.play();

            //removes everything off the stage
            // avoids duplication of objects
            for (var i = app.stage.children.length - 1; i >= 0; i--) {

                app.stage.removeChild(app.stage.children[i]);

            };

            //displays the background image to the stage
            app.stage.addChild(background);

            // Changes to next question
            changeQuestion();
            console.log(questionNumber);

        } else {
            //plays the wrong file file and displays the text on the stage
            wrongSound.play();
            message.text = "please try again";
        }

    }

    function changeQuestion() {

        //increments questionNumber and enters into the next array
        questionNumber++;

        //if questionNumber is than the choiceSelection length
        if (questionNumber < choiceSelection.length) {

            //display the next question
            displayQuestion();

        } else {

            //if not then show the finish slide
            finish();
        }

    }

    //if mouse left click is released change texture
    function onButtonUp() {
        this.isdown = false;
        if (this.isOver) {
            this.texture = textureButtonOver;
        } else {
            this.texture = textureButton;
        }
    }

    //if mouse left click is over change texture
    function onButtonOver() {
        this.isOver = true;
        if (this.isdown) {
            return;
        }
        this.texture = textureButtonOver;
    }


    function onButtonOut() {
        this.isOver = false;
        if (this.isdown) {
            return;
        }
        this.texture = textureButton;
    }

    //increments hidden id called hiddenScore value
    function incrementSc() {
        $('#hiddenScore').val(function(i, score) {
            return ++score;
        });
    }

    //increments hidden id called hiddenAttempt value
    function incrementAt() {
        $('#hiddenAttempt').val(function(i, attempt) {
            return ++attempt;
        });
    }



}); //doc ready




</script>
</body>
