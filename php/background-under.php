<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="background under">
            <!-- <div class="bubble bubble--1"></div>
            <div class="bubble bubble--2"></div>
            <div class="bubble bubble--3"></div>
            <div class="bubble bubble--4"></div>
            <div class="bubble bubble--5"></div>
            <div class="bubble bubble--6"></div>
            <div class="bubble bubble--7"></div>
            <div class="bubble bubble--8"></div>
            <div class="bubble bubble--9"></div>
            <div class="bubble bubble--10"></div>
            <div class="bubble bubble--11"></div>
            <div class="bubble bubble--12"></div> -->
        </div>
    </body>
    <style>
        .under {
            background-image: linear-gradient(0, var(--gradient-bgrnd1), var(--gradient-bgrnd2));
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .bubble {
            aspect-ratio: 1;
            border-radius: 100%;
            position: absolute;
            background-color: white;
            bottom: -50px;
            opacity: 0.2;
            animation: bubble 15s ease-in-out infinite,
                sideWays 4s ease-in-out infinite alternate;
        }

        @keyframes bubble {
            0% {
                transform: translateY(0%);
                opacity: 0.03;
            }
            90% {
                opacity: .1;
            }
            100% {
                transform: translateY(var(--background-under-height));
                opacity: 0;
            }
        }

        @keyframes sideWays {
            0% {
                margin-left: 0px;
            }
            100% {
                margin-left: 200px;
            }
        }
    </style>
</html>