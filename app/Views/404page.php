<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page not found</title>
    <style>
        body {
            font-family: DM Sans;
            margin-inline: auto;
            text-align: center;

        }

       
        a{
          position: fixed;
          top: 50%;
          left:50%;
          transform: translate(-50%, -50%);
            text-decoration: none;
           
        }
        .btn {
            color: #fff;
            background: #4154f1;
            padding: 8px 30px;
            border-radius: .3rem;
        }

        @media (min-width: 992px) {
            img {

                max-width: 50%;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="container">

            <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
           
              <img src="<?= asset("img/Oops! 404 Error with a broken robot-rafiki.svg") ?>" class="img-fluid py-5" alt="Page Not Found">
              <a class="btn" href="<?= URL ?>">Retorne</a><br>
                <div class="credits">
                    Made by 
                </div>
            </section>

        </div>
    </main>
</body>

</html>