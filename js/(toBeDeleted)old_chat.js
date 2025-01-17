document.addEventListener('DOMContentLoaded', () => {
  let lastMessageId = 0; 
  let lastBotMessage = ''; 

  // Load the chat list
  function loadChats(searchTerm = '') {
    fetch(`../handlers/get_chats.php?search=${encodeURIComponent(searchTerm)}`)
      .then(response => response.text())
      .then(data => {
        try {
          const jsonData = JSON.parse(data);
          const chatList = document.getElementById('chatList');
          chatList.innerHTML = '';

          if (jsonData.length === 0) {
            chatList.innerHTML = '<li class="text-muted">No users found.</li>';
          }

          jsonData.forEach(chat => {
            let listItem = document.createElement('li');
            listItem.classList.add('chatList', 'my-1', 'rounded-1');

            let notification = chat.unread_count > 0 ? `<span class="badge bg-danger ms-auto">${chat.unread_count}</span>` : '';
            listItem.innerHTML = `
              <a href="#" class="d-flex align-items-center text-dark text-decoration-none p-2" 
                 onclick="loadChat(${chat.account_id}, '${chat.firstname} ${chat.lastname}', '${chat.account_image}', this)">
                <img src="../assets/images/default_profile.png" alt="Profile" class="rounded-circle me-3" height="40" width="40">
                <div>
                  <strong>${chat.firstname} ${chat.lastname}</strong>
                </div>
                ${notification}
              </a>`;
            chatList.appendChild(listItem);
          });

          // Add chatbot item after loading other chats
          loadChatbot();
        } catch (error) {
          console.error('Error parsing JSON:', error);
          console.log('Raw response data:', data);
        }
      });
  }

  function loadChatbot() {
    const chatList = document.getElementById('chatList');
    const chatbotItem = document.createElement('li');
    chatbotItem.classList.add('chatList', 'my-1', 'rounded-1');

    chatbotItem.innerHTML = `
      <a href="#" class="d-flex align-items-center text-dark text-decoration-none p-2" 
         onclick="openChatbotConversation()">
        <img src="../assets/images/chatbot_profile.png" alt="Chatbot" class="rounded-circle me-3" height="40" width="40">
        <div>
          <strong>Chatbot</strong>
        </div>
      </a>`;
    chatList.appendChild(chatbotItem);
  }

  // Load chat list on page load
  loadChats();
  
  document.getElementById('searchChat').addEventListener('input', (event) => {
    const searchTerm = event.target.value;
    loadChats(searchTerm);
  });

  function formatMessage(message) {
    message = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    const lines = message.split('\n');
    let formattedMessage = '';
    lines.forEach(line => {
      line = line.trim();
      if (line.startsWith('* ')) {
        formattedMessage += `<li>${line.substring(2)}</li>`;
      } else {
        formattedMessage += `<p>${line}</p>`;
      }
    });
    formattedMessage = `<ul>${formattedMessage}</ul>`;
    return formattedMessage;
  }

  function escapeHtml(unsafe) {
    return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function sendMessage() {
    const messageInput = document.getElementById('messageInput').value;
    const receiverId = window.currentChatAccountId;
  
    if (!messageInput) {
      console.log('Message input is empty');
      return;
    }
  
    if (!receiverId) {
      sendChatbotConversation(messageInput);
      return;
    }
  
    const chatMessages = document.getElementById('chatMessages');
    const messageElement = document.createElement('div');
    messageElement.classList.add('d-flex', 'align-items-end', 'justify-content-end', 'mb-3');
  
    const escapedMessage = escapeHtml(messageInput);
    messageElement.innerHTML = `
      <div class="bg-primary text-light p-2 rounded-3" style="max-width: 52%; white-space: pre-wrap;">${escapedMessage}</div>
      <img src="../assets/images/default_profile.png" a lt="Profile" class="rounded-circle ms-3" height="30" width="30">`;
  
    chatMessages.appendChild(messageElement);
    document.getElementById('messageInput').value = '';
    scrollChatToBottom();
  
    fetch('../handlers/send_message.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `message=${encodeURIComponent(messageInput)}&receiver_id=${receiverId}`,
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        console.error('Error from server:', data.error);
        return;
      }
  
      lastMessageId = data.message_id || lastMessageId;
      scrollChatToBottom();
    })
    .catch(error => {
      console.error('Error sending message:', error);
    });
  }  

  function removeAsterisks(message) {
    return message.replace(/\*\*(.*?)\*\*/g, '$1');
  }

  function fetchNewMessages() {
    const receiverId = window.currentChatAccountId;
  
    fetch('../handlers/fetch_messages.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `chat_with=${receiverId}&last_message_id=${lastMessageId}`,
    })
    .then(response => response.json())
    .then(messages => {
      const chatMessages = document.getElementById('chatMessages');
  
      messages.forEach(msg => {
        const isSender = msg.sender_id === window.currentChatAccountId;
    
        let messageContent = msg.message;

        if (!isSender) {
          return;
        }
    
        const messageElement = document.createElement('div');
        messageElement.classList.add(
          'd-flex',           
          isSender ? 'flex-row-reverse' : 'flex-row',
          'align-items-end', 
          'justify-content-end', 
          'mb-3'
        );
    
        const messageDiv = document.createElement('div');
        messageDiv.classList.add(isSender ? 'bg-secondary' : 'bg-primary', 'text-light', 'p-2', 'rounded-3');
        messageDiv.style.maxWidth = '52%';
        messageDiv.style.whiteSpace = 'pre-wrap';
        messageDiv.style.wordBreak = 'break-word';
    
        messageDiv.textContent = msg.message;
    
        const img = document.createElement('img');
        img.src = '../assets/images/default_profile.png';
        img.alt = 'Profile';
        img.classList.add('rounded-circle', isSender ? 'me-3' : 'ms-3');
        img.height = 30;
        img.width = 30;
    
        messageElement.appendChild(messageDiv);
        messageElement.appendChild(img);
    
        chatMessages.appendChild(messageElement);
    
        lastMessageId = msg.id;
      });    
  
      scrollChatToBottom();
    })
    .catch(error => {
      console.error('Error fetching new messages:', error);
    });
  }  

  document.getElementById('searchChat').addEventListener('input', (event) => {
    const searchTerm = event.target.value;
    loadChats(searchTerm);
  });

  loadChats();

  function scrollChatToBottom(forceScroll = false) {
    const chatMessages = document.getElementById('chatMessages');
    
    if (!chatMessages) {
      console.error('Chat messages container not found.');
      return;
    }
  
    const isNearBottom = chatMessages.scrollHeight - chatMessages.scrollTop <= chatMessages.clientHeight + 100;
  
    if (isNearBottom || forceScroll) {
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  }  

  document.getElementById('sendMessage').addEventListener('click', sendMessage);

  document.getElementById('messageInput').addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
      if (event.shiftKey) {
        return;
      } else {
        event.preventDefault();
        sendMessage();
      }
    }
  });  

  document.getElementById('messageInput').addEventListener('input', (event) => {
    const textarea = event.target;
    textarea.style.height = '40px';
    textarea.style.height = textarea.scrollHeight + 'px';
  });  

  const messageInput = document.getElementById('messageInput');

  messageInput.addEventListener('input', (event) => {
    messageInput.style.height = '40px';
    messageInput.style.height = messageInput.scrollHeight + 'px';
  });
  
  // Listen for the "Enter" key and send the message
  messageInput.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && !event.shiftKey) {
      event.preventDefault();
      
      sendMessage();
    
      messageInput.style.height = '40px'; 
    }
  });

  // Function to load chat box when a user selects a chat
  const chatSidePanel = document.getElementById('chat_sidepanel');
  const chatBox = document.getElementById('chat_box');
  const chatList = document.getElementById('chatList');
  
  window.loadChat = function(accountId, fullName, profileImage, chatElement) {
    const isMobile = window.innerWidth <= 768;
  
    if (isMobile) {
      document.body.classList.add('show-chat-box');
    }
  
    window.currentChatAccountId = accountId;
  
    const chatUser = document.getElementById('chatUser');
    chatUser.textContent = fullName;
  
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.innerHTML = '';
  
    fetch('../handlers/mark_messages_read.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `chat_with=${accountId}`,
    });

    const notificationBadge = chatElement.querySelector('.badge');
    if (notificationBadge) {
      notificationBadge.remove();
    }
  
    fetch('../handlers/fetch_messages.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `chat_with=${accountId}`,
    })
    .then(response => response.json())
    .then(messages => {
      const chatMessages = document.getElementById('chatMessages');
      chatMessages.innerHTML = '';

      document.getElementById('chatUser').textContent = fullName;
      document.querySelector('.head img').src = profileImage ? `../assets/images/${profileImage}` : '../assets/images/default_profile.png';

      messages.forEach(msg => {
        const isSender = msg.sender_id === window.currentChatAccountId;
        const messageElement = document.createElement('div');
        messageElement.classList.add(
          'd-flex', 
          isSender ? 'flex-row-reverse' : 'flex-row',
          'align-items-end', 
          'justify-content-end', 
          'mb-3'
        );
        const messageDiv = document.createElement('div');
        messageDiv.classList.add(isSender ? 'bg-secondary' : 'bg-primary', 'text-light', 'p-2', 'rounded-3');
        messageDiv.style.maxWidth = '52%';
        messageDiv.style.whiteSpace = 'pre-wrap';
        messageDiv.style.wordBreak = 'break-word';
        messageDiv.textContent = msg.message;
  
        const img = document.createElement('img');
        img.src = '../assets/images/default_profile.png';
        img.alt = 'Profile';
        img.classList.add('rounded-circle', isSender ? 'me-3' : 'ms-3');
        img.height = 30;
        img.width = 30;
  
        messageElement.appendChild(messageDiv);
        messageElement.appendChild(img);
        chatMessages.appendChild(messageElement);

        lastMessageId = msg.id;
      });
  
      scrollChatToBottom(true);
  
      setInterval(fetchNewMessages, 5000);
    })
    .catch(error => {
      console.error('Error fetching messages:', error);
    });
  
    document.getElementById('searchChat').value = '';
    loadChats();
  };
  
  document.getElementById('backToChatList').addEventListener('click', () => {
    const isMobile = window.innerWidth <= 768;
    if (isMobile) {
      document.body.classList.remove('show-chat-box');
    }
  });
  
  window.addEventListener('resize', () => {
    const isMobile = window.innerWidth <= 768;
    if (!isMobile) {
      document.body.classList.remove('show-chat-box');
    }
  });  

  // ----------- //
  // CHATBOT JS //
  // ---------- //

  window.openChatbotConversation = function() {
    const accountIdElement = document.getElementById('account_id');
    
    if (!accountIdElement || !accountIdElement.value) {
      console.error('Account ID is missing.');
      return;
    }
    
    const accountId = accountIdElement.value;
  
    // Show chat box when in mobile view
    const isMobile = window.innerWidth <= 768;
    if (isMobile) {
      document.body.classList.add('show-chat-box');
    }
  
    window.currentChatAccountId = null;
    window.accountId = accountId;
  
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.innerHTML = '';
  
    document.getElementById('chatUser').textContent = 'Chatbot';
    document.querySelector('.head img').src = '../assets/images/chatbot_profile.png';
  
    fetch(`../handlers/fetch_chatbot_conversation.php?account_id=${accountId}`)
      .then(response => response.json())
      .then(data => {
        data.forEach(message => {
          if (message.user_message) {
            addMessageToChat(message.user_message, true);  // User message
          }
          if (message.bot_response) {
            addMessageToChat(message.bot_response, false);  // Bot response
          }
        });
        scrollChatToBottom(true); 
      })
      .catch(error => {
        console.error('Error fetching chatbot conversation:', error);
      });
  };  

  window.sendChatbotConversation = function(messageInput) {
    const chatMessages = document.getElementById('chatMessages');
    messageInput = messageInput.trim();
  
    if (!messageInput) {
      console.log('Message input is empty');
      return;
    }
  
    if (!window.accountId) {
      console.error('Account ID is missing for chatbot conversation.');
      return;
    }

    const messageElement = document.createElement('div');
    messageElement.classList.add('d-flex', 'align-items-end', 'justify-content-end', 'mb-3');
  
    const escapedMessage = escapeHtml(messageInput);
    messageElement.innerHTML = `
      <div class="bg-primary text-light p-2 rounded-3" style="max-width: 52%; white-space: pre-wrap;">${escapedMessage}</div>
      <img src="../assets/images/default_profile.png" alt="Profile" class="rounded-circle ms-3" height="30" width="30">`;

    chatMessages.appendChild(messageElement);
    document.getElementById('messageInput').value = '';

    fetch('../handlers/send_message_to_chatbot.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `account_id=${encodeURIComponent(window.accountId)}&message=${encodeURIComponent(messageInput)}`,
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        console.error('Error from server:', data.error);
        return;
      }

      if (data.reply && data.reply !== lastBotMessage) {
        const formattedReply = escapeHtml(data.reply);
        const botMessageElement = document.createElement('div');
        botMessageElement.classList.add('d-flex', 'flex-row-reverse', 'align-items-end', 'justify-content-end', 'mb-3');
        botMessageElement.innerHTML = `
          <div class="bg-secondary text-light p-2 rounded-3" style="max-width: 52%; white-space: pre-wrap;">${formattedReply}</div>
          <img src="../assets/images/chatbot_profile.png" alt="Bot" class="rounded-circle me-3" height="30" width="30">`;

        chatMessages.appendChild(botMessageElement);
        lastBotMessage = data.reply;
  
        scrollChatToBottom(true);
      }
    })
    .catch(error => {
      console.error('Error sending chatbot message:', error);
    });
  };

  // Make addMessageToChat globally accessible by attaching to window
  window.addMessageToChat = function(text, isBotResponse) {
    const messageBox = document.getElementById('chatMessages');
    const messageElement = document.createElement('div');

    messageElement.classList.add(
      'd-flex', 
      isBotResponse ? 'flex-row' : 'flex-row-reverse',
      'align-items-end', 
      'justify-content-end', 
      'mb-3'
    );

    const messageDiv = document.createElement('div');
    messageDiv.classList.add(isBotResponse ? 'bg-primary' : 'bg-secondary', 'text-light', 'p-2', 'rounded-3');
    messageDiv.style.maxWidth = '52%';
    messageDiv.style.whiteSpace = 'pre-wrap';
    messageDiv.style.wordBreak = 'break-word';
    messageDiv.innerText = text;

    const img = document.createElement('img');
    img.src = isBotResponse ? '../assets/images/default_profile.png' : '../assets/images/chatbot_profile.png';
    img.alt = isBotResponse ? 'User Profile' : 'Bot Profile';
    img.classList.add('rounded-circle', isBotResponse ? 'ms-3' : 'me-3');
    img.height = 30;
    img.width = 30;

    messageElement.appendChild(messageDiv);
    messageElement.appendChild(img);

    messageBox.appendChild(messageElement);
  };
});