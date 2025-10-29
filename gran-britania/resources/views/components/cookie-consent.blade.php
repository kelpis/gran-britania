<div id="cookie-consent" class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 w-[95%] sm:w-auto max-w-lg bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] p-4 rounded shadow-md" style="display: none;">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <div class="flex-1 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
            Usamos cookies propias y de terceros para mejorar tu experiencia y análisis. Puedes aceptar todas las cookies o rechazarlas. Más información en
            <a href="{{ url('/cookies') }}" class="underline text-[#f53003] dark:text-[#FF4433]">la política de cookies</a>.
        </div>

        <div class="flex items-center gap-2">
            <button id="cookie-accept" class="px-3 py-2 bg-[#1b1b18] text-white rounded">Aceptar</button>
            <button id="cookie-reject" class="px-3 py-2 border rounded">Rechazar</button>
        </div>
    </div>

    <script>
        (function(){
            function setCookie(name, value, days) {
                var expires = "";
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days*24*60*60*1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "")  + expires + "; path=/";
            }
            function getCookie(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
            }

            var banner = document.getElementById('cookie-consent');
            var acceptBtn = document.getElementById('cookie-accept');
            var rejectBtn = document.getElementById('cookie-reject');

            function showBanner() {
                if (banner) banner.style.display = 'block';
            }
            function hideBanner() {
                if (banner) banner.style.display = 'none';
            }

            var consent = getCookie('cookies_consent');
            if (!consent) {
                // show banner if no decision yet
                showBanner();
            }

            if (acceptBtn) acceptBtn.addEventListener('click', function(e){
                setCookie('cookies_consent', 'accepted', 365);
                hideBanner();
                // dispatch event so other scripts can init analytics
                window.dispatchEvent(new CustomEvent('cookies:accepted'));
            });

            if (rejectBtn) rejectBtn.addEventListener('click', function(e){
                setCookie('cookies_consent', 'rejected', 365);
                hideBanner();
                window.dispatchEvent(new CustomEvent('cookies:rejected'));
            });
        })();
    </script>
</div>
