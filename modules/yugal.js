const YugalVars = {
  root: document.getElementById("yugal-root"),
  yugalHeadComment: `<!-- DO NOT DELETE THIS COMMENT! THIS COMMENT IS VERY IMPORTANT FOR YUGAL TO WORK! -->`,
  pageNum: 1,
  importedCss: 0,
};
var page = {};
const yugal = {
  err404page: {},
  production: () => {
    console.log(
      "%cThis is a browser feature intended for developers. Do not enter anything here.",
      "background:black ;color: white; font-size: x-large"
    );
    console = {
      log: () => {},
      error: () => {},
      warn: () => {},
    };
  },
  afterDestroyed: () => {},
  title: (title) => {
    if (document.getElementsByTagName("title")[0] === undefined) {
      yugal.header("<title></title>");
    }
    if (title !== undefined) {
      document.getElementsByTagName("title")[0].innerHTML = title;
      return true;
    } else {
      return document.getElementsByTagName("title")[0].innerHTML;
    }
  },
  error404: (props) => {
    yugal.err404page = props;
  },
  meta: (title, content) => {
    if (document.getElementsByTagName("meta")[title] !== undefined) {
      document.getElementsByTagName("meta")[title].content = content;
    } else {
      let headTag = document.getElementsByTagName("head")[0].innerHTML;
      let siteCredential = headTag.split(YugalVars.yugalHeadComment)[0];
      siteCredential = `
              ${siteCredential}
              <meta name="${title}" content="${content}" />
          `;
      document.getElementsByTagName("head")[0].innerHTML = `${siteCredential}${
        YugalVars.yugalHeadComment
      }${headTag.split(YugalVars.yugalHeadComment)[1]}`;
    }
  },
  header: (code) => {
    document.querySelectorAll("[data-yugal]").forEach((prev_tag) => {
      prev_tag.remove();
    });
    _temp = document.createElement("div");
    _temp.innerHTML = code;
    __headtags = _temp.querySelectorAll("*");
    __headtags.forEach((tag) => {
      document.getElementsByTagName("head")[0].appendChild(tag);
      tag.setAttribute("data-yugal", "");
    });
  },
  allPages: {},
  page: ({
    render,
    willMount,
    willUnMount,
    didMount,
    didUnMount,
    uri,
    css,
    header,
  }) => {
    yugal.allPages[uri] = {
      render,
      willMount,
      willUnMount,
      didMount,
      didUnMount,
      css,
      header,
    };
  },
  projectRoot: "",
  loadAnchors: () => {
    const all_anchors = document.querySelectorAll("a");
    all_anchors.forEach((anchor) => {
      attr = anchor.getAttribute("href");
      nya = attr;
      if (
        attr[0] === "." &&
        attr[1] === "/" &&
        attr[2] !== "." &&
        attr[2] !== "/"
      ) {
        attr = attr.replace("./", "/");
        final = `${yugal.projectRoot}${attr}`;
        anchor.setAttribute("href", final);
      }
      target_attr = anchor.getAttribute("target");
      if (target_attr !== "_blank") {
        if (nya[0] !== "/") {
          if (yugal.allPages[attr] !== undefined) {
            onclickattr = anchor.getAttribute("onclick");
            if (onclickattr !== null) {
              final = `${onclickattr};yugal.link("${attr}", event);`;
              final = final.replaceAll(";;", ";");
              anchor.setAttribute("onclick", final);
            } else {
              final = `yugal.link("${attr}", event);`;
              anchor.setAttribute("onclick", final);
            }
          }
        }
      }
    });
  },
  currentDestroy: () => {},
  updatePageFromUrl: () => {
    yugal.loadAnchors();
    const uri = window.location.href;
    let req = uri.split("/");
    req = req[req.length - 1];
    yugal.link(`/${req}`);
  },
  _willUnMount: () => {},
  _didUnMount: () => {},
  _willMount: () => {},
  _didMount: () => {},
  runLifeCycleMethods: () => {
    const uri = window.location.href;
    let req = uri.split("/");
    req = req[req.length - 1];
    let screen = {};
    if (yugal.allPages[`/${req}`] === undefined) {
      screen = yugal.err404page;
    } else {
      screen = yugal.allPages[`/${req}`];
    }
    yugal._willMount =
      screen.willMount === undefined ? () => {} : screen.willMount;
    yugal._didMount =
      screen.didMount === undefined ? () => {} : screen.didMount;
    yugal._willUnMount =
      screen.willUnMount === undefined ? () => {} : screen.willUnMount;
    yugal._didUnMount =
      screen._didUnMount === undefined ? () => {} : screen.didUnMount;
    page = {};
    yugal.loadAnchors();
    yugal._willMount();
    yugal._didMount();
  },
  link: (uri, event) => {
    page = {};
    if (event !== undefined) {
      event.preventDefault();
    }
    yugal._willUnMount();
    yugal._willUnMount = () => {};
    let __tempdid = () => {};
    function navigationLocale(screen) {
      yugal._willMount =
        screen.willMount === undefined ? () => {} : screen.willMount;
      yugal._didMount =
        screen.didMount === undefined ? () => {} : screen.didMount;
      yugal._willUnMount =
        screen.willUnMount === undefined ? () => {} : screen.willUnMount;
      __tempdid =
        screen._didUnMount === undefined ? () => {} : screen.didUnMount;
      window.history.pushState(null, null, `.${uri}`);
      page = {};
      yugal._willMount();
      yugal.loadAnchors();
      if (screen.header !== undefined) {
        yugal.header(screen.header);
      }
      YugalVars.root.innerHTML = screen.render;
      const elements = document.querySelectorAll("[to]");
      elements.forEach((element) => {
        let toValue = element.getAttribute("to");
        if (element.getAttribute("onclick") !== null) {
          toValue_past = element.getAttribute("onclick");
        } else {
          toValue_past = "";
        }
        if (toValue_past.replaceAll(" ") === "") {
          toValue = `yugal.link("${toValue}");`;
        } else {
          toValue = `${toValue_past};yugal.link("${toValue}");`;
        }
        toValue = toValue.replaceAll(";;", ";");
        element.setAttribute("onclick", toValue);
        element.removeAttribute("to");
      });
      document.getElementById("yugal-page-specific-style").innerHTML =
        screen.css;
      yugal._didMount();
    }
    if (yugal.allPages[uri] == undefined) {
      if (Object.keys(yugal.err404page).length === 0) {
        console.error("ERROR 404: PAGE NOT FOUND");
      } else {
        navigationLocale(yugal.err404page);
      }
    } else {
      navigationLocale(yugal.allPages[uri]);
    }
    yugal._didUnMount();
    yugal._didUnMount = () => {};
    yugal.didUnMount = __tempdid;
    yugal.loadAnchors();
  },
  include: (file) => {
    var script = document.createElement("script");
    script.src = file;
    script.type = "text/javascript";
    document.getElementsByTagName("body").item(0).appendChild(script);
  },
  files: (array) => {
    array.map((item) => {
      yugal.include(item);
    });
  },
  kebabize: (str) => {
    return str
      .split("")
      .map((letter, idx) => {
        return letter.toUpperCase() === letter
          ? `${idx !== 0 ? "-" : ""}${letter.toLowerCase()}`
          : letter;
      })
      .join("");
  },
  style: (obj) => {
    des = "";
    Object.keys(obj).forEach(function (nkey) {
      end = "";
      if (typeof obj[nkey] === "number") {
        end = `${obj[nkey]}px;`;
      } else {
        end = `${obj[nkey]};`;
      }
      des = des + "" + yugal.kebabize(nkey) + ":" + end;
    });
    return des;
  },
  css: (props, name) => {
    if (document.getElementById("yugal-style") === null){
      const style_element = document.createElement("style");
      style_element.setAttribute("id", "yugal-style");
      document.getElementsByTagName("body")[0].appendChild(style_element);
    }
    if (typeof props !== "string") {
      props = yugal.style(props);
    }
    yugal_style = document.getElementById("yugal-style");
    yugal_style.innerHTML = `${yugal_style.innerHTML}${name}{${props}}`;
  },
  $: (key) => document.querySelector(key),
  StyleSheet: {
    create: (css, beg) => {
      beg = !beg ? "" : beg;
      final_css = "";
      Object.keys(css).map((key) => {
        this_props = `${beg}${key}{`;
        Object.keys(css[key]).map((prop) => {
          prop_val =
            typeof css[key][prop] === "number"
              ? `${String(css[key][prop])}px`
              : css[key][prop];
          this_props = `${this_props}${yugal.kebabize(prop)}:${prop_val};`;
        });
        this_props = this_props + `} `;
        final_css = final_css + this_props;
      });
      return final_css;
    },
    inject: (css_string) => {
      if (document.getElementById("yugal-style") === null){
        const style_element = document.createElement("style");
        style_element.setAttribute("id", "yugal-style");
        document.getElementsByTagName("body")[0].appendChild(style_element);
      }
      document.getElementById("yugal-style").innerHTML = `${
        document.getElementById("yugal-style").innerHTML
      }${css_string}`;
    },
    import: (url, id) => {
      id = !id ? `yugal_imported_css${YugalVars.importedCss}` : id;
      headcode = document.getElementsByTagName("head")[0].innerHTML;
      headcode = headcode.split(YugalVars.yugalHeadComment);
      headcode[0] = `<link rel="stylesheet" href="${url}" id="${id}">${headcode[0]}`;
      document.getElementsByTagName(
        "head"
      )[0].innerHTML = `${headcode[0]}${YugalVars.yugalHeadComment}${headcode[1]}`;
      YugalVars.importedCss = YugalVars.importedCss + 1;
      return document.getElementById(id);
    },
  },
};
const html = (code) => code;

window.addEventListener("load", function () {
  if (yugal.backend === undefined || yugal.backend !== true) {
    yugal.updatePageFromUrl();
  } else {
    yugal.runLifeCycleMethods();
  }
  yugal.loadAnchors();
});

window.onpopstate = function (event) {
  yugal.updatePageFromUrl();
  yugal.loadAnchors();
};