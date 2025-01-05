<!-- Chat Header -->
<div class="head border-bottom bg-light py-3 px-3">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <button id="backToChatList" class="btn p-0 pe-2 btn-light d-flex d-md-none">
                <i class='bx bx-left-arrow-alt fs-3'></i>
              </button>
              <img src="../assets/images/defualt_profile.png" alt="Profile" class="rounded-circle me-3" height="40" width="40">
              <span id="chatUser">Select a user to start chatting</span>
            </div>
            <div>
              <i class='bx bx-dots-horizontal-rounded fs-4'></i>
            </div>
          </div>
        </div>

        <!-- Chat Messages -->
        <div id="chatMessages" class="body flex-grow-1 d-flex flex-column p-3 bg-light">
          <!-- Messages will be dynamically loaded here -->
        </div>

        <!-- Chat Input -->
        <div class="chat_input d-flex align-items-end p-3 border-top bg-light">
          <textarea type="text" id="messageInput" class="form-control border-2 text-dark me-3" placeholder="Type your message"></textarea>
          <button id="sendMessage" type="button" class="btn btn-light d-flex justify-content-center">
            <i class='bx bx-send text-dark fs-4'></i>
          </button>
        </div>

