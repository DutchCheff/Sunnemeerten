<?php if (config('debug') && config('mail')['driver'] === 'log' && currentPage() !== 'mailbox'): ?>
    <a href="?page=mailbox" class="fixed bottom-1 left-1 z-30 bg-accent-light text-background text-xs px-2 py-0.5 rounded-full shadow-md opacity-70 hover:opacity-100">
        Mailbox (dev)
    </a>
<?php endif; ?>
</body>

</html>
