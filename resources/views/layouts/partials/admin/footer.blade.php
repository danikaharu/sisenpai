<footer>
    <div class="footer clearfix mb-0 text-muted">
        <div class="float-start">
            <p>2023 © SISENPAI</p>
        </div>
        <div class="float-end">
            <p>Made by
                <a href="https://kominfo.bonebolangokab.go.id" target="_blank">KOMINFO BONE BOLANGO</a>
            </p>
        </div>
    </div>
</footer>
</div>

<script src="{{ asset('template/admin') }}/extensions/jquery/jquery.min.js"></script>
<script src="{{ asset('template/admin') }}/js/bootstrap.js"></script>
@include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
<script src="{{ asset('template/admin') }}/js/app.js"></script>
@stack('js')
</body>

</html>
