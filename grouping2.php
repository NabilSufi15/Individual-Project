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
  header("Location:home.php");

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
        let image, box, box2, box3, circle;
        var q, c1, c2, c3, checkButton;
        var correctSound = new Audio('sound/correct.wav');
        var wrongSound = new Audio('sound/wrong.wav');

        $.getJSON('template.json', function(json) {

            if (json[13].data) {
                //gets the game name, template, id and stores into hidden variable
                hiddenName.value = json[13].game_name;
                hiddenTemplate.value = json[13].game_template;
                hiddenID.value = json[13].game_id;

                // loops through the json template file and retrieves the elements
                for (i = 0; i < json[13].data.length; i++) {
                    choiceSelection[i] = new Array;
                    choiceSelection[i][0] = json[13].data[i].group1Text;
                    choiceSelection[i][1] = json[13].data[i].group1Image1;
                    choiceSelection[i][2] = json[13].data[i].group1Image2;
                    choiceSelection[i][3] = json[13].data[i].group1Image3;
                    choiceSelection[i][4] = json[13].data[i].group2Text;
                    choiceSelection[i][5] = json[13].data[i].group2Image1;
                    choiceSelection[i][6] = json[13].data[i].group2Image2;
                    choiceSelection[i][7] = json[13].data[i].group2Image3;
                }

                //loads data in displayQuestion
                displayQuestion();
            }



        })


        //displays game data on the screen
        function displayQuestion() {

          //stores the choiceSelection data within sepearate variable
          q1 = choiceSelection[questionNumber][0];
          c1 = choiceSelection[questionNumber][1];
          c2 = choiceSelection[questionNumber][2];
          c3 = choiceSelection[questionNumber][3];
          q2 = choiceSelection[questionNumber][4];
          c4 = choiceSelection[questionNumber][5];
          c5 = choiceSelection[questionNumber][6];
          c6 = choiceSelection[questionNumber][7];



          //creates the first box and displays it
          box = new Graphics();
          box.lineStyle(4, 0x000000, 1);
          box.beginFill(0xFFFFFF); // colour of box white
          box.drawRect(0, 0, 400, 250);
          box.endFill();
          box.x = 50;
          box.y = 300;
          app.stage.addChild(box);

          //creates second box and displays it
          box2 = new Graphics();
          box2.lineStyle(4, 0x000000, 1);
          box2.beginFill(0xFFFFFF); // colour of box white
          box2.drawRect(0, 0, 400, 250);
          box2.endFill();
          box2.x = 500;
          box2.y = 300;
          app.stage.addChild(box2);

          //creates the pool box and displays it
          pool = new Graphics();
          pool.lineStyle(4, 0x000000, 1);
          pool.beginFill(0x66CCFF); // colour blue
          pool.drawRect(0, 0, 800, 150);
          pool.endFill();
          pool.x = 75;
          pool.y = 75;
          app.stage.addChild(pool);

          // source: http://flashbynight.com/tutes/pixquiz/
          // Gives a random number between 1 and 3
          // rnd will decide the positoins of the choice selections
          var rnd = Math.random() * 3;
          rnd = Math.ceil(rnd);

          // if rnd is 1, position of the images will be c1,c4,c2,c5,c3,c6
          if (rnd == 1) {

            //Stores the image from the template json file and displays it
            image = new Sprite(c1);
            image.x = 175;
            image.y = 150;
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

            //Stores the image from the template json file and displays it
            image4 = new Sprite(c4);
            image4.x = 300;
            image4.y = 150;
            image4.width = 100;
            image4.height = 100;
            image4.buttonMode = true;
            image4.anchor.set(0.5);
            image4.interactive = true;

            image4
                .on('pointerdown', onDragStart)
                .on('pointerup', onDragEnd)
                .on('pointerupoutside', onDragEnd)
                .on('pointermove', onDragMove);

            app.stage.addChild(image4);

            //Stores the image from the template json file and displays it
            image2 = new Sprite(c2);
            image2.x = 425;
            image2.y = 150;
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

            //Stores the image from the template json file and displays it
            image5 = new Sprite(c5);
            image5.x = 550;
            image5.y = 150;
            image5.width = 100;
            image5.height = 100;
            image5.buttonMode = true;
            image5.anchor.set(0.5);
            image5.interactive = true;

            image5
                .on('pointerdown', onDragStart)
                .on('pointerup', onDragEnd)
                .on('pointerupoutside', onDragEnd)
                .on('pointermove', onDragMove);

            app.stage.addChild(image5);

            //Stores the image from the template json file and displays it
            image3 = new Sprite(c3);
            image3.x = 675;
            image3.y = 150;
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
            image6 = new Sprite(c6);
            image6.x = 800;
            image6.y = 150;
            image6.width = 100;
            image6.height = 100;
            image6.buttonMode = true;
            image6.anchor.set(0.5);
            image6.interactive = true;

            image6
                .on('pointerdown', onDragStart)
                .on('pointerup', onDragEnd)
                .on('pointerupoutside', onDragEnd)
                .on('pointermove', onDragMove);

            app.stage.addChild(image6);
          }

            // if rnd is 2, position of the images will be c3,c6,c1,c4,c2,c5
          if (rnd == 2) {

            //Stores the image from the template json file and displays it
            image3 = new Sprite(c3);
            image3.x = 175;
            image3.y = 150;
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
            image6 = new Sprite(c6);
            image6.x = 300;
            image6.y = 150;
            image6.width = 100;
            image6.height = 100;
            image6.buttonMode = true;
            image6.anchor.set(0.5);
            image6.interactive = true;

            image6
                .on('pointerdown', onDragStart)
                .on('pointerup', onDragEnd)
                .on('pointerupoutside', onDragEnd)
                .on('pointermove', onDragMove);

            app.stage.addChild(image6);

            //Stores the image from the template json file and displays it
            image = new Sprite(c1);
            image.x = 425;
            image.y = 150;
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

            //Stores the image from the template json file and displays it
            image4 = new Sprite(c4);
            image4.x = 550;
            image4.y = 150;
            image4.width = 100;
            image4.height = 100;
            image4.buttonMode = true;
            image4.anchor.set(0.5);
            image4.interactive = true;

            image4
                .on('pointerdown', onDragStart)
                .on('pointerup', onDragEnd)
                .on('pointerupoutside', onDragEnd)
                .on('pointermove', onDragMove);

            app.stage.addChild(image4);

            //Stores the image from the template json file and displays it
            image2 = new Sprite(c2);
            image2.x = 675;
            image2.y = 150;
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

            //Stores the image from the template json file and displays it
            image5 = new Sprite(c5);
            image5.x = 800;
            image5.y = 150;
            image5.width = 100;
            image5.height = 100;
            image5.buttonMode = true;
            image5.anchor.set(0.5);
            image5.interactive = true;

            image5
                .on('pointerdown', onDragStart)
                .on('pointerup', onDragEnd)
                .on('pointerupoutside', onDragEnd)
                .on('pointermove', onDragMove);

            app.stage.addChild(image5);
          }

          // if rnd is 3, position of the images will be c2,c5,c3,c6,c1,c4
          if (rnd == 3) {

            //Stores the image from the template json file and displays it
            image2 = new Sprite(c2);
            image2.x = 175;
            image2.y = 150;
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

            //Stores the image from the template json file and displays it
            image5 = new Sprite(c5);
            image5.x = 300;
            image5.y = 150;
            image5.width = 100;
            image5.height = 100;
            image5.buttonMode = true;
            image5.anchor.set(0.5);
            image5.interactive = true;

            image5
                .on('pointerdown', onDragStart)
                .on('pointerup', onDragEnd)
                .on('pointerupoutside', onDragEnd)
                .on('pointermove', onDragMove);

            app.stage.addChild(image5);

            //Stores the image from the template json file and displays it
            image3 = new Sprite(c3);
            image3.x = 425;
            image3.y = 150;
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
            image6 = new Sprite(c6);
            image6.x = 550;
            image6.y = 150;
            image6.width = 100;
            image6.height = 100;
            image6.buttonMode = true;
            image6.anchor.set(0.5);
            image6.interactive = true;

            image6
                .on('pointerdown', onDragStart)
                .on('pointerup', onDragEnd)
                .on('pointerupoutside', onDragEnd)
                .on('pointermove', onDragMove);

            app.stage.addChild(image6);

            //Stores the image from the template json file and displays it
            image = new Sprite(c1);
            image.x = 675;
            image.y = 150;
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

            //Stores the image from the template json file and displays it
            image4 = new Sprite(c4);
            image4.x = 800;
            image4.y = 150;
            image4.width = 100;
            image4.height = 100;
            image4.buttonMode = true;
            image4.anchor.set(0.5);
            image4.interactive = true;

            image4
                .on('pointerdown', onDragStart)
                .on('pointerup', onDragEnd)
                .on('pointerupoutside', onDragEnd)
                .on('pointermove', onDragMove);

            app.stage.addChild(image4);

          }

          // the designs of the fonts
          let style = new TextStyle({fontFamily: "sans-serif", fontSize: 30, fill: "blue",  fontWeight: "bolder"});
          let style2 = new TextStyle({fontFamily: "sans-serif", fontSize: 80, fill: "black", fontWeight: "bolder"});

          //displays the group1Text from the template json file
          text = new Text(q1, style);
          text.position.set(250, 285);
          text.anchor.set(0.5, 0.5);
          app.stage.addChild(text);

          //displays the group2Text from the template json file
          text2 = new Text(q2, style);
          text2.position.set(700, 285);
          text2.anchor.set(0.5, 0.5);
          app.stage.addChild(text2);

          //displays the score and attempt on the game screen
          scores = new Text(score + "/" + attempt, style);
          scores.position.set(900, 30);
          scores.anchor.set(0.5, 0.5);
          app.stage.addChild(scores);

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

          // if image, image2, image 3 is in box and image4, image5, image6 is in box2, it is correct
          if(checkCollision(image, box) && checkCollision(image2, box) && checkCollision(image3, box) && checkCollision(image4, box2) && checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = true;
            box.tint = 0x7FFF00; //green
            box2.tint = 0x7FFF00; //green

          // if image, image2, image 3 are not in box and image4, image5, image6 are not in box2, it is incorrect
          // move all incorrect images to pool
          }else if(!checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && !checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0xff0000;
            box2.tint = 0xff0000;
            image.x = 175;
            image.y = 150;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;
            image4.x = 550;
            image4.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("all wrong");

          // if image, image2, image 3 are in box and image4, image5, image6 are not in box2, it is incorrect
          // move image4,image5,image6 back to pool
          }else if(checkCollision(image, box) && checkCollision(image2, box) && checkCollision(image3, box) && !checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0xff0000;
            image4.x = 550;
            image4.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("group1 right");

          // if image, image2, image 3 are in box and image4,image6 are not in box2 but image5 is, it is incorrect
          // move image4, image6 back to pool
          }else if(checkCollision(image, box) && checkCollision(image2, box) && checkCollision(image3, box) && !checkCollision(image4, box2) && checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image4.x = 550;
            image4.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("group1 right g2i2");

            // if image, image2, image 3 are in box and image4,image5 are not in box2 but image6 is, it is incorrect
            // move image4 and image5 back to pool
          }else if(checkCollision(image, box) && checkCollision(image2, box) && checkCollision(image3, box) && !checkCollision(image4, box2) && !checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image4.x = 550;
            image4.y = 150;
            image5.x = 675;
            image5.y = 150;

            // alert("group1 right g2i3");

            // if image, image2, image 3 are in box and image4 are not in box2 but image5 and image6 is, it is incorrect
            // move image4 back to pool
          }else if(checkCollision(image, box) && checkCollision(image2, box) && checkCollision(image3, box) && !checkCollision(image4, box2) && checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image4.x = 550;
            image4.y = 150;

            // alert("group1 right g2i3 i2");

            // if image, image2, image 3 are in box and image5 are not in box2 but image4 and image6 is, it is incorrect
            // move image5 back to pool
          }else if(checkCollision(image, box) && checkCollision(image2, box) && checkCollision(image3, box) && checkCollision(image4, box2) && !checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image5.x = 675;
            image5.y = 150;

            // alert("group1 right g2i3 i1");

            // if image, image2, image 3 are in box and image6 are not in box2 but image4 and image5 is, it is incorrect
            // move image6 back to pool
          }else if(checkCollision(image, box) && checkCollision(image2, box) && checkCollision(image3, box) && checkCollision(image4, box2) && checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image6.x = 800;
            image6.y = 150;

            // alert("group1 right g2i1 i2");

            // if image4, image5, image 6 are in box2 and image, image2, image3 are not in box2, it is incorrect
            // move image,image2,image3 back to pool
          }else if(!checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && checkCollision(image4, box2) && checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0xff0000;
            box2.tint = 0x7FFF00;
            image.x = 175;
            image.y = 150;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;

            // alert("group2 right");

            // if image is in box and image2,image3 are not in box and image4, image5, image 6 are not in box2, it is incorrect
            // move image2, image3, image4, image5, image6 back to pool
          }else if(checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && !checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0xff0000;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;
            image4.x = 550;
            image4.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g1I right");

            // if image2 is in box and image,image3 are not in box and image4, image5, image 6 are not in box2, it is incorrect
            // move image, image3, image4, image5, image6 back to pool
          }else if(!checkCollision(image, box) && checkCollision(image2, box) && !checkCollision(image3, box) && !checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0xff0000;
            image.x = 175;
            image.y = 150;
            image3.x = 425;
            image3.y = 150;
            image4.x = 550;
            image4.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g1I2 right");

            // if image3 is in box and image,image3 are not in box and image4, image5, image 6 are not in box2, it is incorrect
            // move image, image2, image4, image5, image6 back to pool
          }else if(!checkCollision(image, box) && !checkCollision(image2, box) && checkCollision(image3, box) && !checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0xff0000;
            image.x = 175;
            image.y = 150;
            image2.x = 300;
            image2.y = 150;
            image4.x = 550;
            image4.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g1I3 right");

            // if image4 is in box2 and image5,image6 are not in box2 and image, image2, image3 are not in box, it is incorrect
            // move image, image2, image3, image5, image6 back to pool
          }else if(!checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0xff0000;
            box2.tint = 0x7FFF00;
            image.x = 175;
            image.y = 150;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g2I right");

            // if image5 is in box2 and image4,image6 are not in box2 and image, image2, image3 are not in box, it is incorrect
            // move image, image2, image3, image4, image6 back to pool
          }else if(!checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && !checkCollision(image4, box2) && checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0xff0000;
            box2.tint = 0x7FFF00;
            image.x = 175;
            image.y = 150;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;
            image4.x = 550;
            image4.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g2I2 right");

            // if image6 is in box2 and image4,image5 are not in box2 and image, image2, image3 are not in box, it is incorrect
            // move image, image2, image3, image4, image5 back to pool
          }else if(!checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && !checkCollision(image4, box2) && !checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0xff0000;
            box2.tint = 0x7FFF00;
            image.x = 175;
            image.y = 150;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;
            image4.x = 550;
            image4.y = 150;
            image5.x = 675;
            image5.y = 150;

            // alert("g2I3 right");

            // if image is in box and image4 is in box2. but image5,image6 are not in box2 and image2, image3 are not in box, it is incorrect
            // move image2, image3, image5, image6 back to pool
          }else if(checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g1I g2I right");

            // if image is in box and image5 is in box2. but image4,image6 are not in box2 and image2, image3 are not in box, it is incorrect
            // move image2, image3, image4, image6 back to pool
          }else if(checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && !checkCollision(image4, box2) && checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;
            image4.x = 550;
            image4.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g1I g2I2 right");

            // if image is in box and image6 is in box2. but image4,image5 are not in box2 and image2, image3 are not in box, it is incorrect
            // move image2, image3, image4, image5 back to pool
          }else if(checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && !checkCollision(image4, box2) && !checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;
            image4.x = 550;
            image4.y = 150;
            image5.x = 675;
            image5.y = 150;

            // alert("g1I g2I3 right");

            // if image is in box but image2,image3 are not in box and image4, image5, image6 are in box2, it is incorrect
            // move image2, image3 back to pool
          }else if(checkCollision(image, box) && !checkCollision(image2, box) && !checkCollision(image3, box) && checkCollision(image4, box2) && checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image2.x = 300;
            image2.y = 150;
            image3.x = 425;
            image3.y = 150;

            // alert("g1I g2all right");

            // if image and image2 is in box but image3 are not in box and image4, image5, image6 are in box2, it is incorrect
            // move image3 back to pool
          }else if(checkCollision(image, box) && checkCollision(image2, box) && !checkCollision(image3, box) && checkCollision(image4, box2) && checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image3.x = 425;
            image3.y = 150;

            // alert("g1I i2 g2all right");

            // if image and image3 is in box but image3 are not in box and image4, image5, image6 are in box2, it is incorrect
            // move image2 back to pool
          }else if(checkCollision(image, box) && !checkCollision(image2, box) && checkCollision(image3, box) && checkCollision(image4, box2) && checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image2.x = 300;
            image2.y = 150;

            // alert("g1I i3 g2all right");

            // if image2 and image3 is in box but image are not in box and image4, image5, image6 are in box2, it is incorrect
            // move image back to pool
          }else if(!checkCollision(image, box) && checkCollision(image2, box) && checkCollision(image3, box) && checkCollision(image4, box2) && checkCollision(image5, box2) && checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image.x = 175;
            image.y = 150;

            // alert("g1I2 I3 g2all right");

            // if image2 is in box and image4 is in box2. but image5,image6 are not in box2 and image, image3 are not in box, it is incorrect
            // move image, image3, image5, image6 back to pool
          }else if(!checkCollision(image, box) && checkCollision(image2, box) && !checkCollision(image3, box) && checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00;
            box2.tint = 0x7FFF00;
            image.x = 175;
            image.y = 150;
            image3.x = 425;
            image3.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g1I2 g2I right");

            // if image3 is in box and image4 is in box2. but image5,image6 are not in box2 and image, image2 are not in box, it is incorrect
            // move image, image2, image5, image6 back to pool
          }else if(!checkCollision(image, box) && !checkCollision(image2, box) && checkCollision(image3, box) && checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00; //green
            box2.tint = 0x7FFF00; //green
            image.x = 175;
            image.y = 150;
            image2.x = 300;
            image2.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g1I3 g2I right");

            // if image, image2, image 3 are in box and image5,image6 are not in box2 but image4 is, it is incorrect
            // move image5, image6 back to pool
          }else if(checkCollision(image, box) && checkCollision(image2, box) && checkCollision(image3, box) && checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00; //green
            box2.tint = 0x7FFF00; //green
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;

            // alert("g1all g2I right");

           // if image and image 3 is in correct box, but the images are not, it is false
           // move image2, image4, image5, image6 back to pool
          }else if(checkCollision(image, box) && !checkCollision(image2, box) && checkCollision(image3, box) && !checkCollision(image4, box2) && !checkCollision(image5, box2) && !checkCollision(image6, box2)){
            correct = false;
            box.tint = 0x7FFF00; // green
            box2.tint = 0xff0000;; // red
            image2.x = 300;
            image2.y = 150;
            image4.x = 550;
            image4.y = 150;
            image5.x = 675;
            image5.y = 150;
            image6.x = 800;
            image6.y = 150;



          }else{
            correct = false;
            // alert("wrong");
          }

            //if all questions correct
            if(correct){
              scores.text = ++score + "/" + attempt;
              //correct = true;
              incrementSc();
              correctSound.play();
              message.text = "right";


              for (var i = app.stage.children.length - 1; i >= 0; i--) {

                app.stage.removeChild(app.stage.children[i]);

              };
              app.stage.addChild(background);
              //questionNumber++;
              changeQuestion();
              console.log(questionNumber);

            }else {
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
