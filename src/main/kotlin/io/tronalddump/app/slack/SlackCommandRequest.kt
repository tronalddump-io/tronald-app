package io.tronalddump.app.slack

import com.fasterxml.jackson.annotation.JsonProperty
import com.fasterxml.jackson.databind.PropertyNamingStrategy
import com.fasterxml.jackson.databind.annotation.JsonNaming

@JsonNaming(PropertyNamingStrategy.SnakeCaseStrategy::class)
data class SlackCommandRequest(
        @JsonProperty("channel_id")
        var channelId: String? = null,

        @JsonProperty("channel_name")
        var channelName: String? = null,

        /**
         * The command that was typed in to trigger this request.
         * This value can be useful if you want to use a single
         * Request URL to service multiple Slash Commands, as it
         * lets you tell them apart.
         */
        @JsonProperty("command")
        var command: String? = null,

        @JsonProperty("enterprise_id")
        var enterpriseId: String? = null,

        @JsonProperty("enterprise_name")
        var enterpriseName: String? = null,

        /**
         * A URL that you can use to respond to the command.
         */
        @JsonProperty("enterprise_url")
        var responseUrl: String? = null,

        @JsonProperty("team_domain")
        var teamDomain: String? = null,

        @JsonProperty("team_id")
        var teamId: String? = null,

        /**
         * This is the part of the Slash Command after the command itself, and it can contain absolutely
         * anything that the user might decide to type. It is common to use this text parameter to provide
         * extra context for the command.
         */
        @JsonProperty("text")
        var text: String? = null,

        /**
         * This is a verification token, a deprecated feature that you shouldn't use any more. It was used
         * to verify that requests were legitimately being sent by Slack to your app, but you should use
         * the signed secrets functionality to do this instead.
         */
        @JsonProperty("token")
        var token: String? = null,

        /**
         * If you need to respond to the command by opening a dialog, you'll need this trigger ID to get
         * it to work. You can use this ID with dialog.open up to 3000ms after this data payload is sent.
         */
        @JsonProperty("trigger_id")
        var triggerId: String? = null,

        /**
         * The ID of the user who triggered the command.
         */
        @JsonProperty("user_id")
        var userId: String? = null,

        /**
         * The plain text name of the user who triggered the command. As above, do not rely on this field
         * as it is being phased out, use the user_id instead.
         */
        @JsonProperty("user_name")
        var userName: String? = null
)
