package io.tronalddump.app

import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.stereotype.Controller
import org.springframework.ui.Model
import org.springframework.ui.set
import org.springframework.web.bind.annotation.GetMapping
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod

@Controller
class IndexController {

    @GetMapping("/")
    @RequestMapping(
            headers = arrayOf(HttpHeaders.ACCEPT + "=" + MediaType.TEXT_HTML_VALUE),
            method = arrayOf(RequestMethod.GET),
            produces = arrayOf(MediaType.TEXT_HTML_VALUE),
            value = ["/"]
    )
    fun index(model: Model): String {
        model["title"] = "Home Page"

        return "index"
    }
}