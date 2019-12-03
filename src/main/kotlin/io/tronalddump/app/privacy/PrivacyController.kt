package io.tronalddump.app.privacy

import io.tronalddump.app.Url
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.stereotype.Controller
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.servlet.ModelAndView

@Controller
class PrivacyController {

    @RequestMapping(
            headers = [HttpHeaders.ACCEPT + "=" + MediaType.TEXT_HTML_VALUE],
            method = [RequestMethod.GET],
            produces = [MediaType.TEXT_HTML_VALUE],
            value = [Url.PRIVACY]
    )
    fun get(): ModelAndView {
        return ModelAndView("privacy")
    }
}