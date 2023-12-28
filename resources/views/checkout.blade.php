<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: none;
            text-transform: capitalize;
            transition: all .2s linear;
        }

body{
    background: #FFB6C1;
}
       
        .bubbles
{
    position: relative;
    display: flex;
}
.bubbles span
{
    position: relative;
    width: 30px;
    height: 30px;
    background: #4fc3dc;
    margin: 0 4px;
    border-radius: 50%;
    box-shadow: 0 0 0 10px #4fc3dc44,
    0 0 50px #4fc3dc,
    0 0 100px #4fc3dc;
    animation: animate 15s linear infinite;
    animation-duration: calc(120s / var(--i));
}
.bubbles span:nth-child(even)
{
    background: #ff2d75;
    box-shadow: 0 0 0 10px #ff2d7544,
    0 0 50px #ff2d75,
    0 0 100px #ff2d75;
}
@keyframes animate
{
    0%
    {
        transform: translateY(100vh) scale(0);
    }
    100%
    {
        transform: translateY(-125vh) scale(1);
    }
}

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            min-height: 100vh;
        }

        .container form {
            padding: 20px;
            width: 700px;
            background: #fff;
            box-shadow: 0 5px 10px rgba(0,0,0,.1);
        }

        .container form .row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .container form .row .col {
            flex: 1 1 250px;
        }

        .container form .row .col .title {
            font-size: 20px;
            color: #333;
            padding-bottom: 5px;
            text-transform: uppercase;
        }

        .container form .row .col .inputBox {
            margin: 15px 0;
        }

        .container form .row .col .inputBox span {
            margin-bottom: 10px;
            display: block;
        }

        .container form .row .col .inputBox input {
            width: 100%;
            border: 1px solid #ccc;
            padding: 10px 15px;
            font-size: 15px;
            text-transform: none;
        }

        .container form .row .col .inputBox input:focus {
            border: 1px solid #000;
        }

        .container form .row .col .flex {
            display: flex;
            gap: 15px;
        }

        .container form .row .col .flex .inputBox {
            margin-top: 5px;
        }

        .container form .row .col .inputBox img {
            height: 34px;
            margin-top: 5px;
            filter: drop-shadow(0 0 1px #000);
        }

        .container form .submit-btn {
            width: 100%;
            padding: 12px;
            font-size: 17px;
            background: #27ae60;
            color: #fff;
            margin-top: 5px;
            cursor: pointer;
        }

        .container form .submit-btn:hover {
            background: #2ecc71;
        }
    </style>
</head>
<body>

<div class="container">

    <form action="">

        <div class="row">

            <div class="col">

                <h3 class="title">DIRECCIÓN DE ENVIO</h3>

                <div class="inputBox">
                    <span>Nombre completo :</span>
                    <input type="text" placeholder="nombre">
                </div>
                <div class="inputBox">
                    <span>Correo electrónico :</span>
                    <input type="email" placeholder="example@example.com">
                </div>
                <div class="inputBox">
                    <span>DIRECCIÓN :</span>
                    <input type="text" placeholder="habitación - calle - localidad">
                </div>
                <div class="inputBox">
                    <span>Ciudad :</span>
                    <input type="text" placeholder="el salvador">
                </div>

                <div class="flex">
                    <div class="inputBox">
                        <span>Estado :</span>
                        <input type="text" placeholder="san salvador">
                    </div>
                    <div class="inputBox">
                        <span>Código postal :</span>
                        <input type="text" placeholder="123 456">
                    </div>
                </div>

            </div>

            <div class="col">

                <h3 class="title">PAGO</h3>

                <div class="inputBox">
                    <span>Tarjetas aceptadas:</span>
                    <img src="/images/card_img.png" alt="">
                </div>
                <div class="inputBox">
                    <span>Nombre en la tarjeta :</span>
                    <input type="text" placeholder="mr. john deo">
                </div>
                <div class="inputBox">
                    <span>Número de Tarjeta de Crédito :</span>
                    <input type="number" placeholder="1111-2222-3333-4444">
                </div>
                <div class="inputBox">
                    <span>Mes de vencimiento:</span>
                    <input type="text" placeholder="january">
                </div>

                <div class="flex">
                    <div class="inputBox">
                        <span>Año vencimiento:</span>
                        <input type="number" placeholder="2023">
                    </div>
                    <div class="inputBox">
                        <span>CVV :</span>
                        <input type="text" placeholder="1234">
                    </div>
                </div>

            </div>
    
        </div>

        <input type="submit" value="proceed to checkout" class="submit-btn">

       
    </form>
    

</div>    
    
</body>
</html>