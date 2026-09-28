        </div>{{-- /ap-scroll --}}
        @include('partials.appshell.tabbar')
    </div>{{-- /ap-device --}}
</div>{{-- /ap-wrap --}}
<script>
    (function () {
        var tick = function () {
            var el = document.getElementById('ap-clock');
            if (!el) return;
            var d = new Date(), p = function (n) { return (n < 10 ? '0' : '') + n; };
            el.textContent = p(d.getHours()) + ':' + p(d.getMinutes());
        };
        tick();
        setInterval(tick, 30000);
    })();
</script>
