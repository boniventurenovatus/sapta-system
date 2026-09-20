<div class="space-y-6">
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">To (Recipient) <span class="text-red-500">*</span></label>
        <select name="recipient_id" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Select Recipient</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('recipient_id', $replyTo?->sender_id) == $user->id ? 'selected' : '' }}>
                    {{ $user->username }} — {{ $user->email }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Subject</label>
        <input type="text" name="subject" value="{{ old('subject', $replyTo ? 'Re: ' . $replyTo->subject : '') }}"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Message subject">
    </div>

    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Message <span class="text-red-500">*</span></label>
        <textarea name="body" rows="8" required
            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Write your message...">{{ old('body') }}</textarea>
    </div>
</div>