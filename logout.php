<?php
echo "<script>
    localStorage.removeItem('username');
    localStorage.removeItem('password');
    window.location.href = 'index.php?success=Logged out successfully';
</script>";
?>