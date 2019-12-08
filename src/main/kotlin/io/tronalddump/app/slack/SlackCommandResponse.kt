package io.tronalddump.app.slack

interface SlackCommandResponse {
    val attachments: List<SlackCommandResponseAttachment>
    val iconUrl: String?
    val mrkdwn: Boolean?
    val responseType: SlackResponseType?
    val text: String?
}