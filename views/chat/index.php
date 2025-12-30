<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once '../src/models/Message.php';
$message_model = new Message($db);

$chat_partner_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if ($chat_partner_id) {
    // Handling Message Send
    if ($_POST && isset($_POST['send_message'])) {
        $message_model->sender_id = $_SESSION['user_id'];
        $message_model->receiver_id = $chat_partner_id;
        $message_model->message = $_POST['message'];
        $message_model->send();
        // Refresh to see message
        header("Location: index.php?page=chat&user_id=$chat_partner_id");
        exit;
    }

    // Load Conversation
    $conversation = $message_model->getConversation($_SESSION['user_id'], $chat_partner_id);

    // Get Partner Name (Quick query or pass it)
    $stmt = $db->prepare("SELECT username, avatar_url FROM users WHERE id = ?");
    $stmt->execute([$chat_partner_id]);
    $partner = $stmt->fetch(PDO::FETCH_ASSOC);

} else {
    // Load Inbox
    $inbox = $message_model->getInbox($_SESSION['user_id']);
}
?>

<div class="max-w-6xl mx-auto px-4 py-24 h-[calc(100vh-100px)]">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-full">
        <!-- Sidebar / Inbox -->
        <div
            class="glass rounded-xl p-4 overflow-y-auto md:col-span-1 <?php echo $chat_partner_id ? 'hidden md:block' : ''; ?>">
            <h2 class="text-xl font-bold text-white mb-4 px-2">Messages</h2>
            <div class="space-y-2">
                <?php if (isset($inbox)): ?>
                    <?php if ($inbox->rowCount() > 0): ?>
                        <?php while ($chat = $inbox->fetch(PDO::FETCH_ASSOC)): ?>
                            <a href="index.php?page=chat&user_id=<?php echo $chat['chat_partner_id']; ?>"
                                class="block p-3 rounded-lg hover:bg-white/5 transition flex items-center space-x-3 <?php echo ($chat_partner_id == $chat['chat_partner_id']) ? 'bg-white/10' : ''; ?>">
                                <div class="shrink-0">
                                    <?php if ($chat['partner_avatar']): ?>
                                        <img src="<?php echo htmlspecialchars($chat['partner_avatar']); ?>"
                                            class="w-10 h-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div
                                            class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-white">
                                            <?php echo strtoupper(substr($chat['partner_name'], 0, 1)); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-white font-medium truncate">
                                        <?php echo htmlspecialchars($chat['partner_name']); ?></h4>
                                    <p class="text-xs text-gray-400 truncate">Click to chat</p>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No messages yet.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- If looking at specific chat, maybe allow back to inbox link conceptually, but here we just list -->
                    <a href="index.php?page=chat" class="text-teal-400 text-sm mb-4 block"><i
                            class="fa-solid fa-arrow-left"></i> Back to Inbox</a>
                    <!-- We load inbox via AJAX ideally, but for now full page refresh logic -->
                <?php endif; ?>
            </div>
        </div>

        <!-- Chat Area -->
        <div
            class="glass rounded-xl p-4 md:col-span-2 flex flex-col <?php echo !$chat_partner_id ? 'hidden md:flex items-center justify-center' : ''; ?>">
            <?php if ($chat_partner_id && isset($partner)): ?>
                <!-- Header -->
                <div class="border-b border-gray-700 pb-4 mb-4 flex items-center space-x-3">
                    <a href="index.php?page=chat" class="md:hidden text-gray-400 mr-2"><i
                            class="fa-solid fa-arrow-left"></i></a>
                    <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-700">
                        <?php if ($partner['avatar_url']): ?>
                            <img src="<?php echo htmlspecialchars($partner['avatar_url']); ?>"
                                class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center font-bold text-white">
                                <?php echo strtoupper(substr($partner['username'], 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white"><?php echo htmlspecialchars($partner['username']); ?></h3>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-xs text-gray-400">Online</span>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="flex-grow overflow-y-auto space-y-4 pr-2 mb-4" id="chat-messages">
                    <?php while ($msg = $conversation->fetch(PDO::FETCH_ASSOC)): ?>
                        <?php $is_me = ($msg['sender_id'] == $_SESSION['user_id']); ?>
                        <div class="flex <?php echo $is_me ? 'justify-end' : 'justify-start'; ?>">
                            <div
                                class="max-w-[70%] <?php echo $is_me ? 'bg-primary-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-gray-700 text-gray-200 rounded-r-xl rounded-tl-xl'; ?> p-3 shadow-lg">
                                <p><?php echo htmlspecialchars($msg['message']); ?></p>
                                <span
                                    class="text-[10px] opacity-70 block text-right mt-1"><?php echo date('h:i A', strtotime($msg['created_at'])); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Input -->
                <form method="POST" class="mt-auto flex space-x-2">
                    <input type="hidden" name="send_message" value="1">
                    <input type="text" name="message" required placeholder="Type a message..."
                        class="flex-grow bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500">
                    <button type="submit"
                        class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg transition"><i
                            class="fa-solid fa-paper-plane"></i></button>
                </form>

            <?php else: ?>
                <div class="text-center text-gray-500">
                    <i class="fa-regular fa-comments text-5xl mb-4"></i>
                    <p>Select a conversation to start chatting.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Scroll to bottom of chat
    const chatContainer = document.getElementById('chat-messages');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>