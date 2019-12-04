package io.tronalddump.app.slack

import io.tronalddump.app.Url
import org.springframework.http.HttpHeaders
import org.springframework.http.HttpStatus
import org.springframework.http.MediaType
import org.springframework.stereotype.Controller
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RequestParam
import org.springframework.web.servlet.ModelAndView

@Controller
class SlackController(
        private val slackService: SlackService
) {

    @RequestMapping(
            headers = [HttpHeaders.ACCEPT + "=" + MediaType.TEXT_HTML_VALUE],
            method = [RequestMethod.GET],
            produces = [MediaType.TEXT_HTML_VALUE],
            value = [Url.SLACK_CONNECT]
    )
    fun connect(
            @RequestParam(value = "code", required = false, defaultValue = "") code: String,
            @RequestParam(value = "error", required = false, defaultValue = "") error: String
    ): ModelAndView {
        if (!error.isNullOrEmpty()) {
            val model = ModelAndView("slack/error")

            model.setStatus(HttpStatus.UNAUTHORIZED)
            model.addObject("authorizeUri", slackService.authorizeUri())

            return model
        }

        if (!code.isNullOrEmpty()) {
            val accessToken: AccessToken? = slackService.requestAccessToken(code)

            return ModelAndView("slack/success")
                    .addObject("accessToken", accessToken)
        }

        return ModelAndView("slack/connect")
                .addObject("authorizeUri", slackService.authorizeUri())
    }
}