

    <script type="text/javascript">

	    window._token = "{{ csrf_token() }}";
	    siteDir = "{{ env('APP_URL') }}";


	</script>

<script type="text/javascript" src="{{ asset2('external/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset2('external/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset2('external/helpers.js') }}"></script>



<script type="text/javascript" src="{{ externals('fns.js') }}"></script>