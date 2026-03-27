{{--
    Affiche le résumé des erreurs de validation
    Usage : <x-validation-errors />
--}}

@if($errors->any())
<div class="flex items-start gap-3 px-4 py-4 rounded-xl border mb-5
            bg-red-50 border-red-200 dark:bg-red-500/10 dark:border-red-500/30"
     role="alert">
    <svg class="w-5 h-5 text-red-500 dark:text-red-400 flex-shrink-0 mt-0.5"
         fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd"/>
    </svg>
    <div>
        <p class="font-bold text-sm text-red-800 dark:text-red-400 mb-1.5">
            {{ $errors->count() }} erreur{{ $errors->count() > 1 ? 's' : '' }} à corriger
        </p>
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
                <li class="flex items-center gap-1.5 text-xs text-red-700 dark:text-red-400">
                    <span class="w-1 h-1 rounded-full bg-red-400 dark:bg-red-500 flex-shrink-0"></span>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif