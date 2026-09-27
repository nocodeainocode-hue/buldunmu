{{-- CEP KABUĞU · durum çubuğu (yalnızca masaüstü cihaz çerçevesinde görünür) --}}
<div class="ph-notch" aria-hidden="true">
    <span id="ph-clock">09:41</span>
    <span class="ph-notch__sig">
        <span>●●●○</span>
        <span>5G</span>
        <span class="ph-notch__bat"></span>
    </span>
</div>
<script>
    (function () {
        var tick = function () {
            var el = document.getElementById('ph-clock');
            if (!el) return;
            var d = new Date(), p = function (n) { return (n < 10 ? '0' : '') + n; };
            el.textContent = p(d.getHours()) + ':' + p(d.getMinutes());
        };
        tick();
        setInterval(tick, 30000);
    })();
</script>
