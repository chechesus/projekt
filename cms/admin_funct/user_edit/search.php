<?php
function searchByCategory($category) {
    try{
        include __DIR__. 'api\session.php';

        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $stmt = $conn->prepare("SELECT category FROM articles WHERE category = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($stmt->execute()) {
            $success = true;
        }
    
        
    }
    catch(Exception $ex){
        echo "$ex";
    }
   
}
?>
