package io.tronalddump.app.slack

import com.fasterxml.jackson.annotation.JsonProperty
import java.io.Serializable

class AccessToken(
        @JsonProperty("access_token")
        val accessToken: String? = null,

        @JsonProperty("scope")
        val scope: String? = null,

        @JsonProperty("team_id")
        val teamId: String? = null,

        @JsonProperty("team_name")
        val teamName: String? = null,

        @JsonProperty("user_id")
        val userId: String? = null,

        @JsonProperty("user_name")
        val userName: String? = null
) : Serializable