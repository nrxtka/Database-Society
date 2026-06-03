
</main><!-- /main -->

<script>
// Auto-dismiss alerts after 4s
const alertEl = document.querySelector('.alert');
if (alertEl) {
    setTimeout(() => {
        alertEl.style.transition = 'opacity .5s';
        alertEl.style.opacity = '0';
        setTimeout(() => alertEl.remove(), 500);
    }, 4000);
}
</script>
</body>
</html>
