<?php include 'api/session.php';?> 
<!DOCTYPE html>
<html lang="sk">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Prihlásiť sa</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="grid-container">
        <?php include 'website_elements/menu.php';?> 
    </div>
    <div class="text">
    <h1>Toto je 1. paragraf</h1>
    <p>
        Contrary to popular belief, Lorem Ipsum is not simply random text. 
        It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. 
        Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,
        consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.
        Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC.
        This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..",
        comes from a line in section 1.10.32.
        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.
        Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form,
        accompanied by English versions from the 1914 translation by H. Rackham.    
    </p>
    <br>
    <h2>Toto je 2. paragraf</h2>
    <p>
        Contrary to popular belief, Lorem Ipsum is not simply random text. 
        It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. 
        Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,
        consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.
        Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC.
        This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..",
        comes from a line in section 1.10.32.
        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.
        Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form,
        accompanied by English versions from the 1914 translation by H. Rackham.    
    </p>
</div>
<div class="comment-thread">
    <!-- Comment 1 start -->
    <div class="comment" id="comment-1">
        <div class="comment-heading">
            <div class="comment-voting">
                <button type="button">
                    <span aria-hidden="true">&#9650;</span>
                    <span class="sr-only">Vote up</span>
                </button>
                <button type="button">
                    <span aria-hidden="true">&#9660;</span>
                    <span class="sr-only">Vote down</span>
                </button>
            </div>
            <div class="comment-info">
                <a href="#" class="comment-author">someguy14</a>
                <p class="m-0">
                    22 points &bull; 4 days ago
                </p>
            </div>
        </div>

        <div class="comment-body">
            <p>
                This is really great! I fully agree with what you wrote, and this is sure to help me out in the future. Thank you for posting this.
            </p>
            <button type="button">Reply</button>
            <button type="button">Flag</button>
        </div>

        <div class="replies">
            <!-- Comment 2 start -->
            <div class="comment" id="comment-2">
                <div class="comment-heading">
                    <div class="comment-voting">
                        <button type="button">
                            <span aria-hidden="true">&#9650;</span>
                            <span class="sr-only">Vote up</span>
                        </button>
                        <button type="button">
                            <span aria-hidden="true">&#9660;</span>
                            <span class="sr-only">Vote down</span>
                        </button>
                    </div>
                    <div class="comment-info">
                        <a href="#" class="comment-author"><?php echo($_SESSION["username"])?></a>
                        <p class="m-0">
                            4 points &bull; 3 days ago
                        </p>
                    </div>
                </div>

                <div class="comment-body">
                    <p>
                        Took the words right out of my mouth!
                    </p>
                    <button type="button">Reply</button>
                    <button type="button">Flag</button>
                </div>
            </div>
            <!-- Comment 2 end -->

            <a href="#load-more">Load more replies</a>
        </div>
    </div>
    <!-- Comment 1 end -->
</div>

<?php include 'footer.php';?>

</body>
</html>