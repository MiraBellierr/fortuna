<x-guest-layout title="Verify your email | Fortuna">
    <div class="w-full max-w-[528px] mx-auto px-6 pt-16 md:pt-24">
        <div class="sheet">
            <div class="sheet-cap">
                <span class="small-label">Fortuna</span>
                <span class="small-label text-[#6E6E6E]">Verify</span>
            </div>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div class="sheet-body">
                    <h1 class="sheet-title">One step before the ledger opens.</h1>
                    <p class="sheet-deck">We sent a link to your email address. Open it, and Fortuna is ready to write in.</p>

                    @if (session('status') == 'verification-link-sent')
                        <p class="mt-4 text-[13.5px] leading-relaxed">A new link has been sent to the email address you signed up with.</p>
                    @endif
                </div>
                <button type="submit" class="btn-primary">Send the link again</button>
                <p class="sheet-foot">One ledger, one user. Nothing is shared.</p>
            </form>
        </div>
        <div class="mt-5 text-[15px] font-medium">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="quiet-link">Log out</button>
            </form>
        </div>
    </div>
</x-guest-layout>
