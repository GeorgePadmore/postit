@auth
        @if (!Auth::user()->hasVerifiedEmail())

            <div class="fixed bottom-0 left-0 z-30 flex w-full p-4 mb-8 ml-4 text-center bg-blue-300 border-blue-200 shadow-xl" style="max-width: 600px;transform: translateX(-50%);left: 50%;">
                <div>
                    <i class="mr-2 text-blue-500 fas fa-exclamation-circle"></i>
                </div>
                <div>
                    Hey {{ Auth::user()->name }}, Your Email isn't verified!
                    <a class="underline hover:text-indigo-500" href="{{ route('verification.send') }}"
                        onclick="event.preventDefault(); document.getElementById('resend-verification-form').submit();">
                        Click Here
                    </a> to Resend.
                    <form id="resend-verification-form" method="POST" action="#"
                        class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        @endif
    @endauth