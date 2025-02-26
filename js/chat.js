var currentOpenedChat = null;
var currentChatRequest = null;
var currentMessagesRequest = null;
var last_message_id = 0;

// USER CHAT FUNCTIONS

function loadChatBox(account_id, chatwith_account_id) {
  if (currentOpenedChat === chatwith_account_id) {
    return;
  } else {
    currentOpenedChat = chatwith_account_id;
  }

  if (currentChatRequest) {
    currentChatRequest.abort();
  }

  if (currentMessagesRequest) {
    currentMessagesRequest.abort();
  }

  currentChatRequest = $.ajax({
    url: "../handlers/chat.load_chatbox.php",
    type: "GET",
    data: {
      account_id: account_id,
      chatwith_account_id: chatwith_account_id,
    },
    success: function (response) {
      $("#chat_box").html(response);
      last_message_id = 0;
      scrollToBottom();
      loadMessages(account_id, chatwith_account_id);
    },
    error: function (xhr, status, error) {
      console.error("Error loading chatbox:", error);
    },
    complete: function () {
      currentChatRequest = null;
    },
  });
}

function update_chatlist(account_id, user_role) {
  var search = $("#searchChat").val();
  $.ajax({
    url:
      "../handlers/chat.load_chatlist.php?search=" +
      search +
      "&account_id=" +
      account_id +
      "&user_role=" +
      user_role,
    type: "GET",
    success: function (response) {
      $("#chatList").html(response);
    },
    error: function (xhr, status, error) {
      console.error("Error updating chatlist:", error);
    },
  });
}

function mark_read(sender_id, receiver_id) {
  $.ajax({
    url: "../handlers/chat.mark_read.php",
    type: "GET",
    data: {
      sender_id: sender_id,
      receiver_id: receiver_id,
    },
    success: function (response) {
      $("#unread" + sender_id).remove();
    },
    error: function (xhr, status, error) {
      console.error("Error marking messages as read:", error);
    },
  });
}

function loadMessages(account_id, chatwith_account_id) {
  currentMessagesRequest = $.ajax({
    url: "../handlers/chat.load_messages.php",
    type: "GET",
    data: {
      account_id: account_id,
      chatwith_account_id: chatwith_account_id,
      last_message_id: last_message_id,
    },
    success: function (response) {
      $("#chatMessages").append(response);
      scrollToBottom();
      updateLastMessageId();
      mark_read(chatwith_account_id, account_id);
      currentMessagesRequest = null;
      loadMessages(account_id, chatwith_account_id);
    },
    error: function (xhr, status, error) {
      console.error("Error loading messages:", error);
      if (status !== "abort") {
        console.error("Error loading messages:", error);
        setTimeout(function () {
          loadMessages(account_id, chatwith_account_id);
        }, 5000);
      }
    },
  });
}

function updateLastMessageId() {
  let messages = $("#chatMessages").children();
  if (messages.length > 0) {
    last_message_id = messages.last().data("message-id");
  }
}

function scrollToBottom() {
  var chatMessages = document.getElementById("chatMessages");
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

// CHATBOT FUNCTIONS

function loadBotChatBox(account_id) {
  if (currentOpenedChat === "chatbot") {
    return;
  } else {
    currentOpenedChat = "chatbot";
  }

  if (currentChatRequest) {
    currentChatRequest.abort();
  }

  if (currentMessagesRequest) {
    currentMessagesRequest.abort();
  }

  currentChatRequest = $.ajax({
    url: "../handlers/chatbot.load_chatbox.php",
    type: "GET",
    data: {
      account_id: account_id,
    },
    success: function (response) {
      $("#chat_box").html(response);
      last_message_id = 0;
      scrollToBottom();
      loadBotMessages(account_id);
    },
    error: function (xhr, status, error) {
      console.error("Error loading chatbox:", error);
    },
    complete: function () {
      currentChatRequest = null;
    },
  });
}

function loadBotMessages(account_id) {
  currentMessagesRequest = $.ajax({
    url: "../handlers/chatbot.load_messages.php",
    type: "GET",
    data: {
      account_id: account_id,
      last_message_id: last_message_id,
    },
    success: function (response) {
      $("#chatMessages").append(response);
      scrollToBottom();
      updateLastMessageId();
      currentMessagesRequest = null;
      loadBotMessages(account_id);
    },
    error: function (xhr, status, error) {
      console.error("Error loading messages:", error);
      if (status !== "abort") {
        console.error("Error loading messages:", error);
        setTimeout(function () {
          loadBotMessages(account_id);
        }, 5000);
      }
    },
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const chatList = document.querySelectorAll("#chatList a"); // All chat list items
  const chatSidePanel = document.getElementById("chat_sidepanel");
  const chatBox = document.getElementById("chat_box");

  // console.log("Script Loaded");

  // if (!chatSidePanel || !chatBox) {
  //   console.error("Error: Required elements not found");
  //   return;
  // }

  // When clicking a chat item
  chatList.forEach(account => {
    account.addEventListener("click", function () {
      // console.log("Chat item clicked");

      if (window.matchMedia("(max-width: 768px)").matches) {
        // console.log("Mobile view detected. Showing chat_box and hiding chat_sidepanel");

        chatSidePanel.style.setProperty("display", "none", "important");
        chatBox.style.setProperty("display", "block", "important");
      }
    });
  });

  // Use Event Delegation to handle the Back button when it appears
  document.body.addEventListener("click", function (event) {
    if (event.target.closest("#backToChatList")) {
      // console.log("Back button clicked");

      if (window.matchMedia("(max-width: 768px)").matches) {
        // console.log("Returning to chat list");

        chatSidePanel.style.setProperty("display", "block", "important");
        chatBox.style.setProperty("display", "none", "important");
      }
    }
  });
});
