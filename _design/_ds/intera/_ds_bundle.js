/* @ds-bundle: {"format":4,"namespace":"INTERADesignSystem_430dc5","components":[{"name":"Badge","sourcePath":"components/core/Badge.jsx"},{"name":"Button","sourcePath":"components/core/Button.jsx"},{"name":"Card","sourcePath":"components/core/Card.jsx"},{"name":"CardHeader","sourcePath":"components/core/Card.jsx"},{"name":"Icon","sourcePath":"components/core/Icon.jsx"},{"name":"IconButton","sourcePath":"components/core/IconButton.jsx"},{"name":"Logo","sourcePath":"components/core/Logo.jsx"},{"name":"Tag","sourcePath":"components/core/Tag.jsx"},{"name":"DataTable","sourcePath":"components/data/DataTable.jsx"},{"name":"MetricTile","sourcePath":"components/data/MetricTile.jsx"},{"name":"SIGNALS","sourcePath":"components/data/SignalBadge.jsx"},{"name":"SignalBadge","sourcePath":"components/data/SignalBadge.jsx"},{"name":"SignalChain","sourcePath":"components/data/SignalChain.jsx"},{"name":"Alert","sourcePath":"components/feedback/Alert.jsx"},{"name":"Dialog","sourcePath":"components/feedback/Dialog.jsx"},{"name":"StatusDot","sourcePath":"components/feedback/StatusDot.jsx"},{"name":"Toast","sourcePath":"components/feedback/Toast.jsx"},{"name":"ToastStack","sourcePath":"components/feedback/Toast.jsx"},{"name":"Tooltip","sourcePath":"components/feedback/Tooltip.jsx"},{"name":"Checkbox","sourcePath":"components/forms/Checkbox.jsx"},{"name":"Field","sourcePath":"components/forms/Field.jsx"},{"name":"Input","sourcePath":"components/forms/Input.jsx"},{"name":"Radio","sourcePath":"components/forms/Radio.jsx"},{"name":"Select","sourcePath":"components/forms/Select.jsx"},{"name":"Switch","sourcePath":"components/forms/Switch.jsx"},{"name":"Textarea","sourcePath":"components/forms/Textarea.jsx"},{"name":"Tabs","sourcePath":"components/navigation/Tabs.jsx"}],"sourceHashes":{"components/core/Badge.jsx":"f85d568408eb","components/core/Button.jsx":"ee19bec844df","components/core/Card.jsx":"e40abfaf142d","components/core/Icon.jsx":"04385864c256","components/core/IconButton.jsx":"111a22b3c00f","components/core/Logo.jsx":"3c6016f578f6","components/core/Tag.jsx":"8aef73a3e58f","components/data/DataTable.jsx":"257b45e765a9","components/data/MetricTile.jsx":"b886f4637ff3","components/data/SignalBadge.jsx":"3364f4e63937","components/data/SignalChain.jsx":"9bef25bfa23c","components/feedback/Alert.jsx":"c2a0be9057e7","components/feedback/Dialog.jsx":"28116bc7710a","components/feedback/StatusDot.jsx":"ff39a23a5e4b","components/feedback/Toast.jsx":"587ffe6ec374","components/feedback/Tooltip.jsx":"f29eb17b4907","components/forms/Checkbox.jsx":"9ce3da19e660","components/forms/Field.jsx":"f574a4f4bb0d","components/forms/Input.jsx":"e7f751771ac2","components/forms/Radio.jsx":"e2d12ded98d4","components/forms/Select.jsx":"2edb042e198d","components/forms/Switch.jsx":"2c77693d7fc8","components/forms/Textarea.jsx":"1be45bdc5292","components/navigation/Tabs.jsx":"9f8076e19955","ui_kits/product/AppShell.jsx":"191540e94229","ui_kits/product/IncidentDetail.jsx":"b8c3d1385249","ui_kits/product/PatternStudio.jsx":"6c530c73b663","ui_kits/product/RoleDashboard.jsx":"a77b9e0107d8","ui_kits/website/HeroSections.jsx":"9167e9dd6f2b","ui_kits/website/OfferSections.jsx":"945e85749d16","ui_kits/website/ProductFrame.jsx":"c3c33354a31f","ui_kits/website/SiteChrome.jsx":"6731f4941453"},"inlinedExternals":[],"unexposedExports":[]} */

(() => {

const __ds_ns = (window.INTERADesignSystem_430dc5 = window.INTERADesignSystem_430dc5 || {});

const __ds_scope = {};

(__ds_ns.__errors = __ds_ns.__errors || []);

// components/core/Card.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const pads = {
  none: 0,
  compact: "var(--pad-card-compact)",
  default: "var(--pad-card)",
  loose: "var(--space-7)"
};
function Card({
  children,
  padding = "default",
  elevated = false,
  accent,
  accentLine,
  interactive = false,
  as: Tag = "div",
  style,
  ...rest
}) {
  const [hover, setHover] = React.useState(false);
  return /*#__PURE__*/React.createElement(Tag, _extends({
    onMouseEnter: interactive ? () => setHover(true) : undefined,
    onMouseLeave: interactive ? () => setHover(false) : undefined,
    style: {
      background: "var(--surface-card)",
      border: "1px solid " + (hover ? "var(--border-strong)" : accent ? accentLine || "var(--border-default)" : "var(--border-card)"),
      // Never emit borderTop:undefined next to the border shorthand — React clears the longhand.
      ...(accent ? {
        borderTop: `3px solid ${accent}`
      } : null),
      borderRadius: "var(--radius-card)",
      padding: pads[padding],
      boxShadow: elevated ? "var(--shadow-md)" : hover ? "var(--shadow-sm)" : "var(--shadow-xs)",
      transition: "var(--transition-surface), border-color var(--duration-fast) var(--ease-standard)",
      cursor: interactive ? "pointer" : undefined,
      ...style
    }
  }, rest), children);
}
function CardHeader({
  title,
  description,
  action,
  icon,
  style,
  ...rest
}) {
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      display: "flex",
      alignItems: "flex-start",
      gap: "var(--space-3)",
      marginBottom: "var(--space-4)",
      ...style
    }
  }, rest), icon ? /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 2,
      color: "var(--ink-500)"
    }
  }, icon) : null, /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-md)",
      fontWeight: "var(--weight-semibold)",
      color: "var(--text-primary)",
      letterSpacing: "var(--tracking-snug)"
    }
  }, title), description ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--text-secondary)",
      marginTop: 2,
      lineHeight: "var(--leading-normal)"
    }
  }, description) : null), action);
}
Object.assign(__ds_scope, { Card, CardHeader });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Card.jsx", error: String((e && e.message) || e) }); }

// components/core/Icon.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const toCamel = k => k.replace(/-([a-z])/g, (_, c) => c.toUpperCase());
const toPascal = n => String(n).replace(/(^|[-_ ])([a-z0-9])/g, (_, __, c) => c.toUpperCase());

/**
 * Thin wrapper around the Lucide icon set (loaded from CDN as window.lucide).
 * INTERA uses Lucide at 1.75px stroke — a hair lighter than Lucide's default —
 * so icons read as quiet annotation rather than decoration.
 */
function Icon({
  name,
  size = 18,
  strokeWidth = 1.75,
  color = "currentColor",
  style,
  ...rest
}) {
  const [, force] = React.useState(0);
  React.useEffect(() => {
    if (typeof window === "undefined" || window.lucide) return;
    const t = setInterval(() => {
      if (window.lucide) {
        clearInterval(t);
        force(n => n + 1);
      }
    }, 60);
    return () => clearInterval(t);
  }, []);
  const set = typeof window !== "undefined" && window.lucide && window.lucide.icons || null;
  const node = set ? set[toPascal(name)] || set[name] : null;
  // Lucide's IconNode is ["svg", attrs, children]; children are [tag, attrs] pairs.
  const children = Array.isArray(node) ? Array.isArray(node[2]) ? node[2] : node : [];
  return /*#__PURE__*/React.createElement("svg", _extends({
    viewBox: "0 0 24 24",
    width: size,
    height: size,
    fill: "none",
    stroke: color,
    strokeWidth: strokeWidth,
    strokeLinecap: "round",
    strokeLinejoin: "round",
    "aria-hidden": "true",
    focusable: "false",
    style: {
      display: "block",
      flex: "none",
      ...style
    }
  }, rest), children.map((child, i) => {
    if (!Array.isArray(child)) return null;
    const [tag, attrs] = child;
    const p = {
      key: i
    };
    for (const k in attrs || {}) p[toCamel(k)] = attrs[k];
    return React.createElement(tag, p);
  }));
}
Object.assign(__ds_scope, { Icon });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Icon.jsx", error: String((e && e.message) || e) }); }

// components/core/Badge.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const BADGE_TONES = {
  neutral: ["var(--status-neutral-soft)", "var(--ink-700)", "var(--status-neutral-line)"],
  info: ["var(--status-info-soft)", "var(--blue-700)", "var(--status-info-line)"],
  ok: ["var(--status-ok-soft)", "var(--green-700)", "var(--status-ok-line)"],
  warning: ["var(--status-warning-soft)", "var(--amber-700)", "var(--status-warning-line)"],
  critical: ["var(--status-critical-soft)", "var(--red-700)", "var(--status-critical-line)"],
  accent: ["var(--violet-50)", "var(--violet-700)", "var(--violet-200)"]
};
function Badge({
  children,
  tone = "neutral",
  icon,
  solid = false,
  style,
  ...rest
}) {
  const [bg, fg, bd] = BADGE_TONES[tone] || BADGE_TONES.neutral;
  return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 5,
      background: solid ? fg : bg,
      color: solid ? "var(--white)" : fg,
      border: `1px solid ${solid ? "transparent" : bd}`,
      borderRadius: "var(--radius-badge)",
      padding: "2px 7px",
      fontSize: "var(--text-xs)",
      fontWeight: "var(--weight-medium)",
      lineHeight: 1.5,
      whiteSpace: "nowrap",
      ...style
    }
  }, rest), icon ? /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon,
    size: 12,
    strokeWidth: 2
  }) : null, children);
}
Object.assign(__ds_scope, { Badge });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Badge.jsx", error: String((e && e.message) || e) }); }

// components/core/Button.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const BTN_CSS = `
.itr-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;font-family:var(--font-sans);font-weight:var(--weight-medium);border-radius:var(--radius-control);border:1px solid transparent;cursor:pointer;text-decoration:none;white-space:nowrap;transition:var(--transition-control);user-select:none}
.itr-btn:disabled,.itr-btn[aria-disabled="true"]{opacity:.45;cursor:not-allowed}
.itr-btn:focus-visible{outline:2px solid var(--focus-ring);outline-offset:2px}
.itr-btn--sm{height:var(--control-height-sm);padding:0 10px;font-size:var(--text-sm)}
.itr-btn--md{height:var(--control-height-md);padding:0 14px;font-size:var(--text-md)}
.itr-btn--lg{height:var(--control-height-lg);padding:0 20px;font-size:var(--text-base)}
.itr-btn--block{width:100%}
.itr-btn--primary{background:var(--action-primary);color:var(--text-on-accent);box-shadow:var(--shadow-xs)}
.itr-btn--primary:hover:not(:disabled){background:var(--action-primary-hover)}
.itr-btn--primary:active:not(:disabled){background:var(--action-primary-active)}
.itr-btn--secondary{background:var(--surface-card);color:var(--ink-800);border-color:var(--action-secondary-border);box-shadow:var(--shadow-xs)}
.itr-btn--secondary:hover:not(:disabled){background:var(--surface-hover);border-color:var(--border-strong)}
.itr-btn--secondary:active:not(:disabled){background:var(--surface-active)}
.itr-btn--ghost{background:transparent;color:var(--ink-700)}
.itr-btn--ghost:hover:not(:disabled){background:var(--surface-hover);color:var(--ink-900)}
.itr-btn--danger{background:var(--status-critical);color:var(--text-on-accent)}
.itr-btn--danger:hover:not(:disabled){background:var(--red-700)}
.itr-btn--link{background:transparent;color:var(--text-link);padding:0;height:auto;border:0}
.itr-btn--link:hover:not(:disabled){color:var(--text-link-hover);text-decoration:underline;text-underline-offset:3px}
.itr-btn--inverse{background:var(--white);color:var(--ink-900)}
.itr-btn--inverse:hover:not(:disabled){background:var(--ink-100)}
.itr-btn--outlineInverse{background:transparent;color:var(--white);border-color:rgba(255,255,255,.34)}
.itr-btn--outlineInverse:hover:not(:disabled){background:rgba(255,255,255,.09);border-color:rgba(255,255,255,.6)}
.itr-btn--outlineInverse:active:not(:disabled){background:rgba(255,255,255,.15)}
`;
if (typeof document !== "undefined" && !document.getElementById("itr-btn-css")) {
  const s = document.createElement("style");
  s.id = "itr-btn-css";
  s.textContent = BTN_CSS;
  document.head.appendChild(s);
}
function Button({
  children,
  variant = "primary",
  size = "md",
  iconLeft,
  iconRight,
  block = false,
  disabled = false,
  href,
  className = "",
  ...rest
}) {
  const Tag = href ? "a" : "button";
  const gsize = size === "lg" ? 18 : 16;
  return /*#__PURE__*/React.createElement(Tag, _extends({
    className: `itr-btn itr-btn--${variant} itr-btn--${size}${block ? " itr-btn--block" : ""} ${className}`,
    href: href,
    disabled: href ? undefined : disabled,
    "aria-disabled": disabled || undefined,
    type: href ? undefined : rest.type || "button"
  }, rest), iconLeft ? /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: iconLeft,
    size: gsize
  }) : null, children, iconRight ? /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: iconRight,
    size: gsize
  }) : null);
}
Object.assign(__ds_scope, { Button });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Button.jsx", error: String((e && e.message) || e) }); }

// components/core/IconButton.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const ICONBTN_CSS = `
.itr-iconbtn{display:inline-flex;align-items:center;justify-content:center;border-radius:var(--radius-control);border:1px solid transparent;background:transparent;color:var(--ink-600);cursor:pointer;transition:var(--transition-control)}
.itr-iconbtn:hover:not(:disabled){background:var(--surface-hover);color:var(--ink-900)}
.itr-iconbtn:active:not(:disabled){background:var(--surface-active)}
.itr-iconbtn:disabled{opacity:.4;cursor:not-allowed}
.itr-iconbtn:focus-visible{outline:2px solid var(--focus-ring);outline-offset:2px}
.itr-iconbtn--outline{border-color:var(--border-default);background:var(--surface-card)}
.itr-iconbtn--outline:hover:not(:disabled){border-color:var(--border-strong)}
.itr-iconbtn--sm{width:28px;height:28px}
.itr-iconbtn--md{width:36px;height:36px}
.itr-iconbtn--lg{width:44px;height:44px}
`;
if (typeof document !== "undefined" && !document.getElementById("itr-iconbtn-css")) {
  const s = document.createElement("style");
  s.id = "itr-iconbtn-css";
  s.textContent = ICONBTN_CSS;
  document.head.appendChild(s);
}
function IconButton({
  icon,
  label,
  variant = "ghost",
  size = "md",
  className = "",
  ...rest
}) {
  return /*#__PURE__*/React.createElement("button", _extends({
    className: `itr-iconbtn itr-iconbtn--${variant} itr-iconbtn--${size} ${className}`,
    "aria-label": label,
    title: label,
    type: "button"
  }, rest), /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon,
    size: size === "sm" ? 15 : size === "lg" ? 20 : 18
  }));
}
Object.assign(__ds_scope, { IconButton });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/IconButton.jsx", error: String((e && e.message) || e) }); }

// components/core/Logo.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/**
 * The INTERA identity. The mark is two overlapping frames — two systems whose
 * agreement is the only solid area. Original artwork; do not redraw or restyle it.
 */
function Mark({
  size,
  tone
}) {
  const inv = tone === "inverse";
  const a = inv ? "#FFFFFF" : "var(--ink-900)";
  const b = inv ? "var(--blue-200)" : "var(--action-primary)";
  const fill = inv ? "#FFFFFF" : "var(--action-primary)";
  return /*#__PURE__*/React.createElement("svg", {
    viewBox: "0 0 40 40",
    width: size,
    height: size,
    fill: "none",
    "aria-hidden": "true",
    style: {
      display: "block",
      flex: "none"
    }
  }, /*#__PURE__*/React.createElement("rect", {
    x: "5",
    y: "5",
    width: "22",
    height: "22",
    rx: "3.5",
    stroke: a,
    strokeWidth: "2.6"
  }), /*#__PURE__*/React.createElement("rect", {
    x: "13",
    y: "13",
    width: "14",
    height: "14",
    fill: fill
  }), /*#__PURE__*/React.createElement("rect", {
    x: "13",
    y: "13",
    width: "22",
    height: "22",
    rx: "3.5",
    stroke: b,
    strokeWidth: "2.6"
  }));
}
function Logo({
  size = 20,
  tone = "ink",
  variant = "horizontal",
  suffix,
  style,
  ...rest
}) {
  const color = tone === "inverse" ? "var(--text-inverse)" : "var(--text-primary)";
  const markSize = Math.round(size * 1.42);
  if (variant === "square") {
    const box = Math.round(size * 2.4);
    return /*#__PURE__*/React.createElement("span", _extends({
      style: {
        display: "inline-flex",
        ...style
      }
    }, rest), /*#__PURE__*/React.createElement("svg", {
      viewBox: "0 0 64 64",
      width: box,
      height: box,
      fill: "none",
      "aria-label": "INTERA",
      role: "img",
      style: {
        display: "block"
      }
    }, /*#__PURE__*/React.createElement("rect", {
      width: "64",
      height: "64",
      rx: "14",
      fill: "var(--ink-900)"
    }), /*#__PURE__*/React.createElement("g", {
      transform: "translate(12 12)"
    }, /*#__PURE__*/React.createElement("rect", {
      x: "0",
      y: "0",
      width: "22",
      height: "22",
      rx: "3.5",
      stroke: "#FFFFFF",
      strokeWidth: "2.8"
    }), /*#__PURE__*/React.createElement("rect", {
      x: "8",
      y: "8",
      width: "14",
      height: "14",
      fill: "#FFFFFF"
    }), /*#__PURE__*/React.createElement("rect", {
      x: "8",
      y: "8",
      width: "22",
      height: "22",
      rx: "3.5",
      stroke: "var(--blue-200)",
      strokeWidth: "2.8"
    }))));
  }
  return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: size * 0.5,
      ...style
    }
  }, rest), variant !== "wordmark" ? /*#__PURE__*/React.createElement(Mark, {
    size: markSize,
    tone: tone
  }) : null, variant !== "mark" ? /*#__PURE__*/React.createElement("span", {
    style: {
      display: "inline-flex",
      alignItems: "baseline",
      gap: "0.55em",
      fontFamily: "var(--font-sans)",
      fontWeight: 600,
      fontSize: size,
      letterSpacing: "0.09em",
      lineHeight: 1,
      color,
      whiteSpace: "nowrap"
    }
  }, "INTERA", suffix ? /*#__PURE__*/React.createElement("span", {
    style: {
      fontWeight: 400,
      fontSize: "0.62em",
      letterSpacing: "0.12em",
      color: tone === "inverse" ? "rgba(255,255,255,.7)" : "var(--text-muted)",
      textTransform: "uppercase"
    }
  }, suffix) : null) : null);
}
Object.assign(__ds_scope, { Logo });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Logo.jsx", error: String((e && e.message) || e) }); }

// components/core/Tag.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Tag({
  children,
  onRemove,
  selected = false,
  icon,
  style,
  ...rest
}) {
  return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 6,
      background: selected ? "var(--blue-50)" : "var(--surface-sunken)",
      color: selected ? "var(--blue-700)" : "var(--ink-700)",
      border: `1px solid ${selected ? "var(--blue-500)" : "var(--border-default)"}`,
      borderRadius: "var(--radius-badge)",
      padding: "3px 8px",
      fontSize: "var(--text-xs)",
      fontFamily: "var(--font-mono)",
      lineHeight: 1.5,
      ...style
    }
  }, rest), icon ? /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon,
    size: 12
  }) : null, children, onRemove ? /*#__PURE__*/React.createElement("button", {
    onClick: onRemove,
    "aria-label": "Remove",
    type: "button",
    style: {
      display: "inline-flex",
      border: 0,
      background: "none",
      padding: 0,
      marginLeft: 1,
      cursor: "pointer",
      color: "inherit",
      opacity: .6
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "x",
    size: 12,
    strokeWidth: 2
  })) : null);
}
Object.assign(__ds_scope, { Tag });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/core/Tag.jsx", error: String((e && e.message) || e) }); }

// components/data/DataTable.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function DataTable({
  columns = [],
  rows = [],
  onRowClick,
  dense = false,
  emptyMessage = "Nothing to show.",
  style,
  ...rest
}) {
  const [hover, setHover] = React.useState(-1);
  const pad = dense ? "6px 12px" : "10px 14px";
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      border: "1px solid var(--border-card)",
      borderRadius: "var(--radius-card)",
      overflow: "hidden",
      background: "var(--surface-card)",
      ...style
    }
  }, rest), /*#__PURE__*/React.createElement("table", {
    style: {
      width: "100%",
      borderCollapse: "collapse",
      fontSize: dense ? "var(--text-sm)" : "var(--text-md)"
    }
  }, /*#__PURE__*/React.createElement("thead", null, /*#__PURE__*/React.createElement("tr", {
    style: {
      background: "var(--surface-sunken)"
    }
  }, columns.map(c => /*#__PURE__*/React.createElement("th", {
    key: c.key,
    style: {
      textAlign: c.align || "left",
      padding: pad,
      fontSize: "var(--text-xs)",
      fontWeight: "var(--weight-semibold)",
      color: "var(--text-secondary)",
      letterSpacing: "var(--tracking-wide)",
      textTransform: "uppercase",
      borderBottom: "1px solid var(--border-default)",
      whiteSpace: "nowrap",
      width: c.width
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 4
    }
  }, c.header, c.sorted ? /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: c.sorted === "desc" ? "chevron-down" : "chevron-up",
    size: 12,
    strokeWidth: 2.2
  }) : null))))), /*#__PURE__*/React.createElement("tbody", null, rows.length === 0 ? /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", {
    colSpan: columns.length,
    style: {
      padding: "var(--space-8)",
      textAlign: "center",
      color: "var(--text-muted)",
      fontSize: "var(--text-sm)"
    }
  }, emptyMessage)) : rows.map((r, i) => /*#__PURE__*/React.createElement("tr", {
    key: r.id || i,
    onMouseEnter: () => setHover(i),
    onMouseLeave: () => setHover(-1),
    onClick: onRowClick ? () => onRowClick(r, i) : undefined,
    style: {
      background: hover === i && onRowClick ? "var(--surface-hover)" : "transparent",
      cursor: onRowClick ? "pointer" : undefined,
      transition: "background-color var(--duration-fast) var(--ease-standard)"
    }
  }, columns.map(c => /*#__PURE__*/React.createElement("td", {
    key: c.key,
    style: {
      padding: pad,
      textAlign: c.align || "left",
      borderBottom: i === rows.length - 1 ? "none" : "1px solid var(--border-subtle)",
      color: "var(--ink-800)",
      fontFamily: c.mono ? "var(--font-mono)" : "inherit",
      fontSize: c.mono ? "var(--text-sm)" : undefined,
      verticalAlign: "middle"
    }
  }, c.render ? c.render(r) : r[c.key])))))));
}
Object.assign(__ds_scope, { DataTable });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/data/DataTable.jsx", error: String((e && e.message) || e) }); }

// components/data/MetricTile.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const DIRS = {
  up: {
    icon: "trending-up"
  },
  down: {
    icon: "trending-down"
  },
  flat: {
    icon: "minus"
  }
};
function MetricTile({
  label,
  value,
  unit,
  delta,
  direction = "flat",
  tone = "neutral",
  note,
  sparkline,
  style,
  ...rest
}) {
  const color = tone === "ok" ? "var(--status-ok)" : tone === "warning" ? "var(--status-warning)" : tone === "critical" ? "var(--status-critical)" : "var(--ink-500)";
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      background: "var(--surface-card)",
      border: "1px solid var(--border-card)",
      borderRadius: "var(--radius-card)",
      padding: "var(--space-4)",
      boxShadow: "var(--shadow-xs)",
      display: "flex",
      flexDirection: "column",
      gap: 6,
      minWidth: 0,
      ...style
    }
  }, rest), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xs)",
      color: "var(--text-secondary)",
      letterSpacing: "var(--tracking-normal)",
      lineHeight: 1.4
    }
  }, label), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "baseline",
      gap: 5,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-2xl)",
      fontWeight: "var(--weight-medium)",
      color: "var(--text-primary)",
      letterSpacing: "-0.01em",
      lineHeight: 1.1
    }
  }, value), unit ? /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--text-muted)"
    }
  }, unit) : null), sparkline, delta || note ? /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 6,
      fontSize: "var(--text-xs)",
      color: "var(--text-muted)"
    }
  }, delta ? /*#__PURE__*/React.createElement("span", {
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 3,
      color,
      fontFamily: "var(--font-mono)"
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: DIRS[direction].icon,
    size: 13,
    strokeWidth: 2
  }), delta) : null, note ? /*#__PURE__*/React.createElement("span", null, note) : null) : null);
}
Object.assign(__ds_scope, { MetricTile });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/data/MetricTile.jsx", error: String((e && e.message) || e) }); }

// components/data/SignalBadge.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
/** The four INTERA object types. Colour and icon are fixed brand vocabulary — do not remap. */
const SIGNALS = {
  event: {
    label: "Event",
    icon: "activity",
    color: "var(--signal-event)",
    soft: "var(--signal-event-soft)",
    border: "var(--signal-event-line)"
  },
  reconciliation: {
    label: "Reconciliation",
    icon: "scale",
    color: "var(--signal-reconciliation)",
    soft: "var(--signal-reconciliation-soft)",
    border: "var(--signal-reconciliation-line)"
  },
  incident: {
    label: "Incident",
    icon: "alert-triangle",
    color: "var(--signal-incident)",
    soft: "var(--signal-incident-soft)",
    border: "var(--signal-incident-line)"
  },
  pattern: {
    label: "Pattern",
    icon: "git-branch",
    color: "var(--signal-pattern)",
    soft: "var(--signal-pattern-soft)",
    border: "var(--signal-pattern-line)"
  }
};
function SignalBadge({
  type = "event",
  label,
  size = "md",
  showIcon = true,
  style,
  ...rest
}) {
  const s = SIGNALS[type] || SIGNALS.event;
  const sm = size === "sm";
  return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: sm ? 4 : 6,
      background: s.soft,
      color: s.color,
      border: `1px solid ${s.border}`,
      borderRadius: "var(--radius-badge)",
      padding: sm ? "1px 6px" : "3px 8px",
      fontSize: sm ? "var(--text-2xs)" : "var(--text-xs)",
      fontWeight: "var(--weight-medium)",
      letterSpacing: "var(--tracking-wide)",
      textTransform: "uppercase",
      lineHeight: 1.6,
      whiteSpace: "nowrap",
      ...style
    }
  }, rest), showIcon ? /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: s.icon,
    size: sm ? 11 : 13,
    strokeWidth: 2
  }) : null, label || s.label);
}
Object.assign(__ds_scope, { SIGNALS, SignalBadge });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/data/SignalBadge.jsx", error: String((e && e.message) || e) }); }

// components/data/SignalChain.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const ORDER = ["event", "reconciliation", "incident", "pattern"];

/** The core INTERA explainer graphic: Event → Reconciliation → Incident → Pattern. */
function SignalChain({
  active,
  captions = {},
  compact = false,
  onSelect,
  style,
  ...rest
}) {
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      display: "flex",
      alignItems: "stretch",
      gap: compact ? "var(--space-2)" : "var(--space-3)",
      flexWrap: "wrap",
      ...style
    }
  }, rest), ORDER.map((k, i) => {
    const s = __ds_scope.SIGNALS[k];
    const on = !active || active === k;
    return /*#__PURE__*/React.createElement(React.Fragment, {
      key: k
    }, /*#__PURE__*/React.createElement("div", {
      onClick: onSelect ? () => onSelect(k) : undefined,
      style: {
        flex: "1 1 0",
        minWidth: compact ? 132 : 168,
        background: on ? s.soft : "var(--surface-sunken)",
        border: `1px solid ${on ? s.border : "var(--border-subtle)"}`,
        borderTop: `3px solid ${on ? s.color : "var(--border-default)"}`,
        borderRadius: "var(--radius-md)",
        padding: compact ? "var(--space-3)" : "var(--space-4)",
        opacity: on ? 1 : .55,
        cursor: onSelect ? "pointer" : undefined,
        transition: "var(--transition-surface), opacity var(--duration-normal) var(--ease-standard)"
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        alignItems: "center",
        gap: 7,
        color: on ? s.color : "var(--ink-500)"
      }
    }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
      name: s.icon,
      size: compact ? 15 : 17
    }), /*#__PURE__*/React.createElement("span", {
      style: {
        fontSize: compact ? "var(--text-sm)" : "var(--text-md)",
        fontWeight: "var(--weight-semibold)",
        color: "var(--ink-900)",
        letterSpacing: "var(--tracking-snug)"
      }
    }, s.label)), captions[k] ? /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: "var(--text-sm)",
        color: "var(--ink-600)",
        marginTop: 6,
        lineHeight: "var(--leading-normal)"
      }
    }, captions[k]) : null), i < ORDER.length - 1 ? /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        alignItems: "center",
        color: "var(--ink-300)",
        flex: "none"
      }
    }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
      name: "chevron-right",
      size: 18
    })) : null);
  }));
}
Object.assign(__ds_scope, { SignalChain });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/data/SignalChain.jsx", error: String((e && e.message) || e) }); }

// components/feedback/Alert.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const ALERT_TONES = {
  info: ["var(--status-info-soft)", "var(--status-info-line)", "var(--status-info)", "info"],
  ok: ["var(--status-ok-soft)", "var(--status-ok-line)", "var(--status-ok)", "check-circle"],
  warning: ["var(--status-warning-soft)", "var(--status-warning-line)", "var(--status-warning)", "alert-triangle"],
  critical: ["var(--status-critical-soft)", "var(--status-critical-line)", "var(--status-critical)", "alert-octagon"],
  neutral: ["var(--surface-sunken)", "var(--border-default)", "var(--ink-500)", "info"]
};
function Alert({
  tone = "info",
  title,
  children,
  icon,
  action,
  onDismiss,
  style,
  ...rest
}) {
  const [bg, bd, fg, defIcon] = ALERT_TONES[tone] || ALERT_TONES.info;
  return /*#__PURE__*/React.createElement("div", _extends({
    role: "status",
    style: {
      display: "flex",
      gap: "var(--space-3)",
      background: bg,
      border: `1px solid ${bd}`,
      borderRadius: "var(--radius-md)",
      padding: "var(--space-3) var(--space-4)",
      ...style
    }
  }, rest), /*#__PURE__*/React.createElement("span", {
    style: {
      color: fg,
      marginTop: 1
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: icon || defIcon,
    size: 18
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0
    }
  }, title ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-md)",
      fontWeight: "var(--weight-semibold)",
      color: "var(--ink-900)",
      lineHeight: 1.4
    }
  }, title) : null, children ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--ink-700)",
      lineHeight: "var(--leading-normal)",
      marginTop: title ? 3 : 0
    }
  }, children) : null, action ? /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: "var(--space-3)"
    }
  }, action) : null), onDismiss ? /*#__PURE__*/React.createElement("button", {
    onClick: onDismiss,
    "aria-label": "Dismiss",
    type: "button",
    style: {
      border: 0,
      background: "none",
      cursor: "pointer",
      color: "var(--ink-500)",
      padding: 0,
      height: 18
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "x",
    size: 16
  })) : null);
}
Object.assign(__ds_scope, { Alert });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/feedback/Alert.jsx", error: String((e && e.message) || e) }); }

// components/feedback/Dialog.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Dialog({
  open = true,
  title,
  description,
  children,
  footer,
  onClose,
  width = 520,
  style,
  ...rest
}) {
  React.useEffect(() => {
    if (!open || !onClose) return;
    const h = e => {
      if (e.key === "Escape") onClose();
    };
    document.addEventListener("keydown", h);
    return () => document.removeEventListener("keydown", h);
  }, [open, onClose]);
  if (!open) return null;
  return /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      inset: 0,
      zIndex: 80,
      display: "grid",
      placeItems: "center",
      padding: "var(--space-6)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    onClick: onClose,
    style: {
      position: "absolute",
      inset: 0,
      background: "rgba(14,26,43,.38)"
    }
  }), /*#__PURE__*/React.createElement("div", _extends({
    role: "dialog",
    "aria-modal": "true",
    "aria-label": typeof title === "string" ? title : undefined,
    style: {
      position: "relative",
      width: "100%",
      maxWidth: width,
      background: "var(--surface-card)",
      border: "1px solid var(--border-default)",
      borderRadius: "var(--radius-lg)",
      boxShadow: "var(--shadow-overlay)",
      overflow: "hidden",
      ...style
    }
  }, rest), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "flex-start",
      gap: "var(--space-4)",
      padding: "var(--space-5) var(--space-6) var(--space-4)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-lg)",
      fontWeight: "var(--weight-semibold)",
      letterSpacing: "var(--tracking-snug)",
      color: "var(--text-primary)"
    }
  }, title), description ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--text-secondary)",
      marginTop: 4,
      lineHeight: "var(--leading-normal)"
    }
  }, description) : null), onClose ? /*#__PURE__*/React.createElement(__ds_scope.IconButton, {
    icon: "x",
    label: "Close",
    size: "sm",
    onClick: onClose
  }) : null), children ? /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "0 var(--space-6) var(--space-5)",
      fontSize: "var(--text-md)",
      color: "var(--ink-700)"
    }
  }, children) : null, footer ? /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      justifyContent: "flex-end",
      gap: "var(--space-2)",
      padding: "var(--space-4) var(--space-6)",
      borderTop: "1px solid var(--border-subtle)",
      background: "var(--surface-sunken)"
    }
  }, footer) : null));
}
Object.assign(__ds_scope, { Dialog });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/feedback/Dialog.jsx", error: String((e && e.message) || e) }); }

// components/feedback/StatusDot.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const COLORS = {
  ok: "var(--status-ok)",
  warning: "var(--status-warning)",
  critical: "var(--status-critical)",
  info: "var(--status-info)",
  neutral: "var(--status-neutral)",
  event: "var(--signal-event)",
  reconciliation: "var(--signal-reconciliation)",
  incident: "var(--signal-incident)",
  pattern: "var(--signal-pattern)"
};
function StatusDot({
  tone = "neutral",
  size = 8,
  label,
  style,
  ...rest
}) {
  const dot = /*#__PURE__*/React.createElement("span", {
    "aria-hidden": "true",
    style: {
      width: size,
      height: size,
      borderRadius: "var(--radius-round)",
      background: COLORS[tone] || COLORS.neutral,
      flex: "none",
      display: "block"
    }
  });
  if (!label) return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      display: "inline-flex",
      ...style
    }
  }, rest), dot);
  return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 7,
      fontSize: "var(--text-sm)",
      color: "var(--ink-700)",
      ...style
    }
  }, rest), dot, label);
}
Object.assign(__ds_scope, { StatusDot });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/feedback/StatusDot.jsx", error: String((e && e.message) || e) }); }

// components/feedback/Toast.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const FG = {
  info: "var(--status-info)",
  ok: "var(--status-ok)",
  warning: "var(--status-warning)",
  critical: "var(--status-critical)"
};
const ICONS = {
  info: "info",
  ok: "check-circle",
  warning: "alert-triangle",
  critical: "alert-octagon"
};
function Toast({
  tone = "info",
  title,
  children,
  onDismiss,
  action,
  style,
  ...rest
}) {
  return /*#__PURE__*/React.createElement("div", _extends({
    role: "status",
    style: {
      display: "flex",
      gap: "var(--space-3)",
      alignItems: "flex-start",
      background: "var(--surface-card)",
      border: "1px solid var(--border-default)",
      borderRadius: "var(--radius-md)",
      boxShadow: "var(--shadow-lg)",
      padding: "var(--space-3) var(--space-4)",
      minWidth: 320,
      maxWidth: 420,
      ...style
    }
  }, rest), /*#__PURE__*/React.createElement("span", {
    style: {
      color: FG[tone],
      marginTop: 1
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: ICONS[tone],
    size: 18
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-md)",
      fontWeight: "var(--weight-medium)",
      color: "var(--ink-900)",
      lineHeight: 1.4
    }
  }, title), children ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--text-secondary)",
      marginTop: 2,
      lineHeight: "var(--leading-normal)"
    }
  }, children) : null, action ? /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: "var(--space-2)"
    }
  }, action) : null), onDismiss ? /*#__PURE__*/React.createElement("button", {
    onClick: onDismiss,
    "aria-label": "Dismiss",
    type: "button",
    style: {
      border: 0,
      background: "none",
      cursor: "pointer",
      color: "var(--ink-400)",
      padding: 0,
      height: 18
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "x",
    size: 16
  })) : null);
}
function ToastStack({
  children,
  style,
  ...rest
}) {
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      position: "fixed",
      right: "var(--space-6)",
      bottom: "var(--space-6)",
      display: "flex",
      flexDirection: "column",
      gap: "var(--space-2)",
      zIndex: 60,
      ...style
    }
  }, rest), children);
}
Object.assign(__ds_scope, { Toast, ToastStack });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/feedback/Toast.jsx", error: String((e && e.message) || e) }); }

// components/feedback/Tooltip.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Tooltip({
  label,
  children,
  placement = "top",
  delay = 120,
  style,
  ...rest
}) {
  const [open, setOpen] = React.useState(false);
  const timer = React.useRef(null);
  const show = () => {
    timer.current = setTimeout(() => setOpen(true), delay);
  };
  const hide = () => {
    clearTimeout(timer.current);
    setOpen(false);
  };
  React.useEffect(() => () => clearTimeout(timer.current), []);
  const pos = {
    top: {
      bottom: "calc(100% + 6px)",
      left: "50%",
      transform: "translateX(-50%)"
    },
    bottom: {
      top: "calc(100% + 6px)",
      left: "50%",
      transform: "translateX(-50%)"
    },
    left: {
      right: "calc(100% + 6px)",
      top: "50%",
      transform: "translateY(-50%)"
    },
    right: {
      left: "calc(100% + 6px)",
      top: "50%",
      transform: "translateY(-50%)"
    }
  }[placement];
  return /*#__PURE__*/React.createElement("span", _extends({
    style: {
      position: "relative",
      display: "inline-flex",
      ...style
    },
    onMouseEnter: show,
    onMouseLeave: hide,
    onFocus: show,
    onBlur: hide
  }, rest), children, open ? /*#__PURE__*/React.createElement("span", {
    role: "tooltip",
    style: {
      position: "absolute",
      ...pos,
      zIndex: 70,
      background: "var(--surface-inverse)",
      color: "var(--text-inverse)",
      fontSize: "var(--text-xs)",
      lineHeight: 1.45,
      padding: "5px 8px",
      borderRadius: "var(--radius-sm)",
      whiteSpace: "nowrap",
      boxShadow: "var(--shadow-md)",
      pointerEvents: "none"
    }
  }, label) : null);
}
Object.assign(__ds_scope, { Tooltip });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/feedback/Tooltip.jsx", error: String((e && e.message) || e) }); }

// components/forms/Checkbox.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Checkbox({
  label,
  description,
  checked,
  indeterminate = false,
  disabled = false,
  onChange,
  id,
  style,
  ...rest
}) {
  const on = checked || indeterminate;
  return /*#__PURE__*/React.createElement("label", {
    htmlFor: id,
    style: {
      display: "inline-flex",
      alignItems: "flex-start",
      gap: 9,
      cursor: disabled ? "not-allowed" : "pointer",
      opacity: disabled ? .5 : 1,
      ...style
    }
  }, /*#__PURE__*/React.createElement("input", _extends({
    id: id,
    type: "checkbox",
    checked: !!checked,
    disabled: disabled,
    onChange: onChange,
    style: {
      position: "absolute",
      opacity: 0,
      width: 16,
      height: 16,
      margin: 0
    }
  }, rest)), /*#__PURE__*/React.createElement("span", {
    "aria-hidden": "true",
    style: {
      width: 16,
      height: 16,
      marginTop: 2,
      flex: "none",
      display: "grid",
      placeItems: "center",
      borderRadius: "var(--radius-xs)",
      border: `1px solid ${on ? "var(--action-primary)" : "var(--border-strong)"}`,
      background: on ? "var(--action-primary)" : "var(--surface-card)",
      color: "var(--white)",
      transition: "var(--transition-control)"
    }
  }, indeterminate ? /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "minus",
    size: 12,
    strokeWidth: 3
  }) : checked ? /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "check",
    size: 12,
    strokeWidth: 3
  }) : null), label ? /*#__PURE__*/React.createElement("span", {
    style: {
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      display: "block",
      fontSize: "var(--text-md)",
      color: "var(--ink-800)",
      lineHeight: 1.4
    }
  }, label), description ? /*#__PURE__*/React.createElement("span", {
    style: {
      display: "block",
      fontSize: "var(--text-xs)",
      color: "var(--text-muted)",
      marginTop: 2
    }
  }, description) : null) : null);
}
Object.assign(__ds_scope, { Checkbox });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Checkbox.jsx", error: String((e && e.message) || e) }); }

// components/forms/Field.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Field({
  label,
  htmlFor,
  hint,
  error,
  required = false,
  children,
  style,
  ...rest
}) {
  return /*#__PURE__*/React.createElement("div", _extends({
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 6,
      ...style
    }
  }, rest), label ? /*#__PURE__*/React.createElement("label", {
    htmlFor: htmlFor,
    style: {
      fontSize: "var(--text-sm)",
      fontWeight: "var(--weight-medium)",
      color: "var(--ink-800)",
      lineHeight: 1.3
    }
  }, label, required ? /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--status-critical)",
      marginLeft: 3
    }
  }, "*") : null) : null, children, error ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xs)",
      color: "var(--status-critical)",
      lineHeight: "var(--leading-normal)"
    }
  }, error) : hint ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xs)",
      color: "var(--text-muted)",
      lineHeight: "var(--leading-normal)"
    }
  }, hint) : null);
}
Object.assign(__ds_scope, { Field });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Field.jsx", error: String((e && e.message) || e) }); }

// components/forms/Input.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const INPUT_FIELD_CSS = `
.itr-input{width:100%;font-family:var(--font-sans);font-size:var(--text-md);color:var(--text-primary);background:var(--surface-card);border:1px solid var(--border-default);border-radius:var(--radius-control);padding:0 12px;height:var(--control-height-md);transition:var(--transition-control)}
.itr-input::placeholder{color:var(--text-muted)}
.itr-input:hover:not(:disabled){border-color:var(--border-strong)}
.itr-input:focus{outline:none;border-color:var(--focus-ring);box-shadow:var(--ring-focus)}
.itr-input:disabled{background:var(--surface-muted);color:var(--text-muted);cursor:not-allowed}
.itr-input--invalid{border-color:var(--status-critical)}
.itr-input--invalid:focus{box-shadow:var(--ring-danger);border-color:var(--status-critical)}
.itr-input--sm{height:var(--control-height-sm);font-size:var(--text-sm);padding:0 9px}
.itr-input--lg{height:var(--control-height-lg);font-size:var(--text-base);padding:0 14px}
.itr-input--area{height:auto;padding:9px 12px;line-height:var(--leading-normal);resize:vertical;min-height:88px}
.itr-input--mono{font-family:var(--font-mono);font-size:var(--text-sm)}
`;
if (typeof document !== "undefined" && !document.getElementById("itr-field-css")) {
  const s = document.createElement("style");
  s.id = "itr-field-css";
  s.textContent = INPUT_FIELD_CSS;
  document.head.appendChild(s);
}
function Input({
  size = "md",
  invalid = false,
  mono = false,
  iconLeft,
  className = "",
  style,
  ...rest
}) {
  const input = /*#__PURE__*/React.createElement("input", _extends({
    className: `itr-input itr-input--${size}${invalid ? " itr-input--invalid" : ""}${mono ? " itr-input--mono" : ""} ${className}`,
    "aria-invalid": invalid || undefined,
    style: iconLeft ? {
      paddingLeft: 34,
      ...style
    } : style
  }, rest));
  if (!iconLeft) return input;
  return /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      display: "block"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      left: 11,
      top: "50%",
      transform: "translateY(-50%)",
      color: "var(--text-muted)",
      pointerEvents: "none"
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: iconLeft,
    size: 16
  })), input);
}
Object.assign(__ds_scope, { Input });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Input.jsx", error: String((e && e.message) || e) }); }

// components/forms/Radio.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Radio({
  label,
  description,
  checked,
  disabled = false,
  onChange,
  name,
  value,
  id,
  style,
  ...rest
}) {
  return /*#__PURE__*/React.createElement("label", {
    htmlFor: id,
    style: {
      display: "inline-flex",
      alignItems: "flex-start",
      gap: 9,
      cursor: disabled ? "not-allowed" : "pointer",
      opacity: disabled ? .5 : 1,
      ...style
    }
  }, /*#__PURE__*/React.createElement("input", _extends({
    id: id,
    type: "radio",
    name: name,
    value: value,
    checked: !!checked,
    disabled: disabled,
    onChange: onChange,
    style: {
      position: "absolute",
      opacity: 0,
      width: 16,
      height: 16,
      margin: 0
    }
  }, rest)), /*#__PURE__*/React.createElement("span", {
    "aria-hidden": "true",
    style: {
      width: 16,
      height: 16,
      marginTop: 2,
      flex: "none",
      borderRadius: "var(--radius-round)",
      border: `1px solid ${checked ? "var(--action-primary)" : "var(--border-strong)"}`,
      background: "var(--surface-card)",
      display: "grid",
      placeItems: "center",
      transition: "var(--transition-control)"
    }
  }, checked ? /*#__PURE__*/React.createElement("span", {
    style: {
      width: 8,
      height: 8,
      borderRadius: "var(--radius-round)",
      background: "var(--action-primary)"
    }
  }) : null), label ? /*#__PURE__*/React.createElement("span", {
    style: {
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      display: "block",
      fontSize: "var(--text-md)",
      color: "var(--ink-800)",
      lineHeight: 1.4
    }
  }, label), description ? /*#__PURE__*/React.createElement("span", {
    style: {
      display: "block",
      fontSize: "var(--text-xs)",
      color: "var(--text-muted)",
      marginTop: 2
    }
  }, description) : null) : null);
}
Object.assign(__ds_scope, { Radio });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Radio.jsx", error: String((e && e.message) || e) }); }

// components/forms/Select.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const SELECT_FIELD_CSS = `
.itr-input{width:100%;font-family:var(--font-sans);font-size:var(--text-md);color:var(--text-primary);background:var(--surface-card);border:1px solid var(--border-default);border-radius:var(--radius-control);padding:0 12px;height:var(--control-height-md);transition:var(--transition-control)}
.itr-input::placeholder{color:var(--text-muted)}
.itr-input:hover:not(:disabled){border-color:var(--border-strong)}
.itr-input:focus{outline:none;border-color:var(--focus-ring);box-shadow:var(--ring-focus)}
.itr-input:disabled{background:var(--surface-muted);color:var(--text-muted);cursor:not-allowed}
.itr-input--invalid{border-color:var(--status-critical)}
.itr-input--invalid:focus{box-shadow:var(--ring-danger);border-color:var(--status-critical)}
.itr-input--sm{height:var(--control-height-sm);font-size:var(--text-sm);padding:0 9px}
.itr-input--lg{height:var(--control-height-lg);font-size:var(--text-base);padding:0 14px}
.itr-input--area{height:auto;padding:9px 12px;line-height:var(--leading-normal);resize:vertical;min-height:88px}
.itr-input--mono{font-family:var(--font-mono);font-size:var(--text-sm)}
`;
if (typeof document !== "undefined" && !document.getElementById("itr-field-css")) {
  const s = document.createElement("style");
  s.id = "itr-field-css";
  s.textContent = SELECT_FIELD_CSS;
  document.head.appendChild(s);
}
function Select({
  options = [],
  size = "md",
  invalid = false,
  placeholder,
  className = "",
  style,
  ...rest
}) {
  return /*#__PURE__*/React.createElement("span", {
    style: {
      position: "relative",
      display: "block"
    }
  }, /*#__PURE__*/React.createElement("select", _extends({
    className: `itr-input itr-input--${size}${invalid ? " itr-input--invalid" : ""} ${className}`,
    style: {
      appearance: "none",
      paddingRight: 32,
      cursor: "pointer",
      ...style
    }
  }, rest), placeholder ? /*#__PURE__*/React.createElement("option", {
    value: ""
  }, placeholder) : null, options.map(o => {
    const v = typeof o === "string" ? o : o.value;
    const l = typeof o === "string" ? o : o.label;
    return /*#__PURE__*/React.createElement("option", {
      key: v,
      value: v
    }, l);
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      right: 10,
      top: "50%",
      transform: "translateY(-50%)",
      color: "var(--ink-500)",
      pointerEvents: "none"
    }
  }, /*#__PURE__*/React.createElement(__ds_scope.Icon, {
    name: "chevron-down",
    size: 16
  })));
}
Object.assign(__ds_scope, { Select });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Select.jsx", error: String((e && e.message) || e) }); }

// components/forms/Switch.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Switch({
  checked = false,
  onChange,
  label,
  disabled = false,
  size = "md",
  id,
  style,
  ...rest
}) {
  const w = size === "sm" ? 30 : 38,
    h = size === "sm" ? 18 : 22,
    k = h - 6;
  return /*#__PURE__*/React.createElement("label", {
    htmlFor: id,
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 10,
      cursor: disabled ? "not-allowed" : "pointer",
      opacity: disabled ? .5 : 1,
      ...style
    }
  }, /*#__PURE__*/React.createElement("input", _extends({
    id: id,
    type: "checkbox",
    role: "switch",
    checked: checked,
    disabled: disabled,
    onChange: onChange,
    style: {
      position: "absolute",
      opacity: 0,
      width: w,
      height: h,
      margin: 0
    }
  }, rest)), /*#__PURE__*/React.createElement("span", {
    "aria-hidden": "true",
    style: {
      width: w,
      height: h,
      flex: "none",
      borderRadius: "var(--radius-round)",
      background: checked ? "var(--action-primary)" : "var(--ink-200)",
      position: "relative",
      transition: "background-color var(--duration-normal) var(--ease-standard)"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      position: "absolute",
      top: 3,
      left: checked ? w - k - 3 : 3,
      width: k,
      height: k,
      borderRadius: "var(--radius-round)",
      background: "var(--white)",
      boxShadow: "var(--shadow-sm)",
      transition: "left var(--duration-normal) var(--ease-standard)"
    }
  })), label ? /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-md)",
      color: "var(--ink-800)"
    }
  }, label) : null);
}
Object.assign(__ds_scope, { Switch });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Switch.jsx", error: String((e && e.message) || e) }); }

// components/forms/Textarea.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const TEXTAREA_FIELD_CSS = `
.itr-input{width:100%;font-family:var(--font-sans);font-size:var(--text-md);color:var(--text-primary);background:var(--surface-card);border:1px solid var(--border-default);border-radius:var(--radius-control);padding:0 12px;height:var(--control-height-md);transition:var(--transition-control)}
.itr-input::placeholder{color:var(--text-muted)}
.itr-input:hover:not(:disabled){border-color:var(--border-strong)}
.itr-input:focus{outline:none;border-color:var(--focus-ring);box-shadow:var(--ring-focus)}
.itr-input:disabled{background:var(--surface-muted);color:var(--text-muted);cursor:not-allowed}
.itr-input--invalid{border-color:var(--status-critical)}
.itr-input--invalid:focus{box-shadow:var(--ring-danger);border-color:var(--status-critical)}
.itr-input--sm{height:var(--control-height-sm);font-size:var(--text-sm);padding:0 9px}
.itr-input--lg{height:var(--control-height-lg);font-size:var(--text-base);padding:0 14px}
.itr-input--area{height:auto;padding:9px 12px;line-height:var(--leading-normal);resize:vertical;min-height:88px}
.itr-input--mono{font-family:var(--font-mono);font-size:var(--text-sm)}
`;
if (typeof document !== "undefined" && !document.getElementById("itr-field-css")) {
  const s = document.createElement("style");
  s.id = "itr-field-css";
  s.textContent = TEXTAREA_FIELD_CSS;
  document.head.appendChild(s);
}
function Textarea({
  invalid = false,
  rows = 4,
  className = "",
  ...rest
}) {
  return /*#__PURE__*/React.createElement("textarea", _extends({
    rows: rows,
    className: `itr-input itr-input--area${invalid ? " itr-input--invalid" : ""} ${className}`,
    "aria-invalid": invalid || undefined
  }, rest));
}
Object.assign(__ds_scope, { Textarea });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/forms/Textarea.jsx", error: String((e && e.message) || e) }); }

// components/navigation/Tabs.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function Tabs({
  items = [],
  value,
  onChange,
  variant = "underline",
  size = "md",
  style,
  ...rest
}) {
  const underline = variant === "underline";
  return /*#__PURE__*/React.createElement("div", _extends({
    role: "tablist",
    style: {
      display: "flex",
      alignItems: "center",
      gap: underline ? "var(--space-6)" : 2,
      borderBottom: underline ? "1px solid var(--border-subtle)" : "none",
      background: underline ? "transparent" : "var(--surface-muted)",
      borderRadius: underline ? 0 : "var(--radius-md)",
      padding: underline ? 0 : 3,
      ...style
    }
  }, rest), items.map(it => {
    const active = it.value === value;
    return /*#__PURE__*/React.createElement("button", {
      key: it.value,
      role: "tab",
      "aria-selected": active,
      type: "button",
      onClick: () => onChange && onChange(it.value),
      style: {
        display: "inline-flex",
        alignItems: "center",
        gap: 7,
        border: 0,
        cursor: "pointer",
        fontFamily: "var(--font-sans)",
        fontSize: size === "sm" ? "var(--text-sm)" : "var(--text-md)",
        fontWeight: active ? "var(--weight-semibold)" : "var(--weight-medium)",
        color: active ? underline ? "var(--text-primary)" : "var(--ink-900)" : "var(--ink-500)",
        background: underline ? "transparent" : active ? "var(--surface-card)" : "transparent",
        boxShadow: !underline && active ? "var(--shadow-xs)" : "none",
        borderRadius: underline ? 0 : "var(--radius-sm)",
        padding: underline ? "0 0 10px" : "5px 12px",
        marginBottom: underline ? -1 : 0,
        borderBottom: underline ? `2px solid ${active ? "var(--action-primary)" : "transparent"}` : "none",
        transition: "var(--transition-control)"
      }
    }, it.icon ? /*#__PURE__*/React.createElement(__ds_scope.Icon, {
      name: it.icon,
      size: 15
    }) : null, it.label, it.count != null ? /*#__PURE__*/React.createElement("span", {
      style: {
        fontFamily: "var(--font-mono)",
        fontSize: "var(--text-2xs)",
        color: active ? "var(--ink-600)" : "var(--ink-400)",
        background: "var(--surface-muted)",
        borderRadius: "var(--radius-xs)",
        padding: "1px 4px"
      }
    }, it.count) : null);
  }));
}
Object.assign(__ds_scope, { Tabs });
})(); } catch (e) { __ds_ns.__errors.push({ path: "components/navigation/Tabs.jsx", error: String((e && e.message) || e) }); }

// ui_kits/product/AppShell.jsx
try { (() => {
const {
  Logo,
  Icon,
  IconButton,
  Input,
  Button,
  Badge,
  StatusDot,
  Tooltip
} = window.INTERADesignSystem_430dc5;
const NAV_GROUPS = [{
  label: "Overview",
  items: [["layout-dashboard", "overview", "Overview", null]]
}, {
  label: "Roles",
  items: [["circle-dollar-sign", "finance", "Finance Control", null], ["gauge", "operations", "Operations Oversight", null], ["scale", "revenue", "Revenue Assurance", 3], ["heart-pulse", "customers", "Customer Health", null], ["shield-check", "integrity", "System Integrity", null]]
}, {
  label: "Work",
  items: [["activity", "events", "Events", 24], ["alert-triangle", "incidents", "Incidents", 3], ["scale", "reconciliations", "Reconciliations", 8], ["git-branch", "patterns", "Pattern Studio", null]]
}, {
  label: "Setup",
  items: [["plug", "connections", "Connections", null], ["settings", "settings", "Settings", null]]
}];
function Sidebar({
  screen,
  onSelect
}) {
  return /*#__PURE__*/React.createElement("aside", {
    style: {
      width: 232,
      flex: "none",
      background: "var(--surface-sunken)",
      borderRight: "1px solid var(--border-subtle)",
      display: "flex",
      flexDirection: "column",
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      height: 56,
      display: "flex",
      alignItems: "center",
      gap: 8,
      padding: "0 16px",
      borderBottom: "1px solid var(--border-subtle)"
    }
  }, /*#__PURE__*/React.createElement(Logo, {
    size: 17
  }), /*#__PURE__*/React.createElement(Badge, {
    tone: "info"
  }, "Beta")), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      overflowY: "auto",
      padding: "12px 10px"
    }
  }, NAV_GROUPS.map(g => /*#__PURE__*/React.createElement("div", {
    key: g.label,
    style: {
      marginBottom: 14
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-2xs)",
      fontWeight: 600,
      letterSpacing: "0.09em",
      textTransform: "uppercase",
      color: "var(--ink-400)",
      padding: "0 8px 6px"
    }
  }, g.label), g.items.map(([ic, id, label, count]) => {
    const on = screen === id;
    return /*#__PURE__*/React.createElement("button", {
      key: id,
      type: "button",
      onClick: () => onSelect(id),
      style: {
        width: "100%",
        display: "flex",
        alignItems: "center",
        gap: 9,
        padding: "7px 8px",
        marginBottom: 1,
        borderRadius: "var(--radius-sm)",
        border: "1px solid " + (on ? "var(--border-subtle)" : "transparent"),
        background: on ? "var(--white)" : "transparent",
        color: on ? "var(--ink-900)" : "var(--ink-600)",
        fontFamily: "var(--font-sans)",
        fontSize: "var(--text-sm)",
        fontWeight: on ? 500 : 400,
        cursor: "pointer",
        textAlign: "left",
        boxShadow: on ? "var(--shadow-xs)" : "none",
        transition: "var(--transition-control)"
      }
    }, /*#__PURE__*/React.createElement(Icon, {
      name: ic,
      size: 15
    }), /*#__PURE__*/React.createElement("span", {
      style: {
        flex: 1,
        minWidth: 0,
        overflow: "hidden",
        textOverflow: "ellipsis",
        whiteSpace: "nowrap"
      }
    }, label), count != null ? /*#__PURE__*/React.createElement("span", {
      style: {
        fontFamily: "var(--font-mono)",
        fontSize: "var(--text-2xs)",
        color: id === "incidents" || count === 3 ? "var(--status-critical)" : "var(--ink-400)"
      }
    }, count) : null);
  })))), /*#__PURE__*/React.createElement("div", {
    style: {
      borderTop: "1px solid var(--border-subtle)",
      padding: "10px 14px",
      display: "flex",
      alignItems: "center",
      gap: 9
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 26,
      height: 26,
      borderRadius: "var(--radius-round)",
      background: "var(--ink-800)",
      color: "var(--white)",
      display: "grid",
      placeItems: "center",
      fontSize: 11,
      fontWeight: 600
    }
  }, "MK"), /*#__PURE__*/React.createElement("div", {
    style: {
      minWidth: 0,
      flex: 1
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xs)",
      fontWeight: 500,
      color: "var(--ink-800)"
    }
  }, "M. Kowalska"), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-2xs)",
      color: "var(--ink-400)"
    }
  }, "Revenue Assurance")), /*#__PURE__*/React.createElement(IconButton, {
    icon: "log-out",
    label: "Sign out",
    size: "sm"
  })));
}
function TopBar({
  title,
  subtitle,
  actions,
  breadcrumb,
  onBack
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      height: 56,
      flex: "none",
      borderBottom: "1px solid var(--border-subtle)",
      background: "var(--surface-page)",
      display: "flex",
      alignItems: "center",
      gap: 12,
      padding: "0 20px"
    }
  }, onBack ? /*#__PURE__*/React.createElement(IconButton, {
    icon: "arrow-left",
    label: "Back",
    size: "sm",
    onClick: onBack
  }) : null, /*#__PURE__*/React.createElement("div", {
    style: {
      minWidth: 0
    }
  }, breadcrumb ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-2xs)",
      color: "var(--ink-400)",
      fontFamily: "var(--font-mono)"
    }
  }, breadcrumb) : null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 9
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-lg)",
      fontWeight: 600,
      letterSpacing: "-0.01em"
    }
  }, title), subtitle)), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }), /*#__PURE__*/React.createElement(Input, {
    size: "sm",
    iconLeft: "search",
    placeholder: "Search",
    style: {
      width: 190
    }
  }), /*#__PURE__*/React.createElement(Tooltip, {
    label: "Last refreshed 09:14 UTC"
  }, /*#__PURE__*/React.createElement(IconButton, {
    icon: "refresh-cw",
    label: "Refresh",
    size: "sm"
  })), /*#__PURE__*/React.createElement(IconButton, {
    icon: "bell",
    label: "Notifications",
    size: "sm"
  }), actions);
}
Object.assign(window, {
  Sidebar,
  TopBar,
  NAV_GROUPS
});
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/product/AppShell.jsx", error: String((e && e.message) || e) }); }

// ui_kits/product/IncidentDetail.jsx
try { (() => {
const {
  Card,
  CardHeader,
  Badge,
  SignalBadge,
  Button,
  DataTable,
  Tabs,
  Icon,
  Tag,
  StatusDot,
  Switch,
  Alert,
  Tooltip
} = window.INTERADesignSystem_430dc5;
const DIFFS = [{
  id: "3391204",
  cust: "Nordwind Logistics",
  usage: "18,402",
  billed: "12,110",
  delta: "€6,292",
  pct: "-34.2%"
}, {
  id: "3391118",
  cust: "Baltic Freight AS",
  usage: "9,860",
  billed: "9,860",
  delta: "€0",
  pct: "0.0%"
}, {
  id: "3390877",
  cust: "Helios Retail Group",
  usage: "44,120",
  billed: "31,004",
  delta: "€13,116",
  pct: "-29.7%"
}, {
  id: "3390512",
  cust: "Karelia Foods",
  usage: "6,204",
  billed: "2,001",
  delta: "€4,203",
  pct: "-67.7%"
}, {
  id: "3390344",
  cust: "Vantaa Telecom",
  usage: "27,880",
  billed: "27,880",
  delta: "€0",
  pct: "0.0%"
}];
function IncidentDetail({
  row,
  onBack
}) {
  const [tab, setTab] = React.useState("records");
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: 20,
      display: "grid",
      gridTemplateColumns: "minmax(0,1fr) 320px",
      gap: 16,
      alignItems: "start",
      overflowY: "auto"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 16,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement(Alert, {
    tone: "critical",
    title: "Usage recorded but not billed for 12 August",
    action: /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        gap: 8
      }
    }, /*#__PURE__*/React.createElement(Button, {
      size: "sm"
    }, "Assign to me"), /*#__PURE__*/React.createElement(Button, {
      size: "sm",
      variant: "secondary"
    }, "Open reconciliation"))
  }, "The daily reconciliation between ", /*#__PURE__*/React.createElement("code", null, "billing.usage_daily"), " and ", /*#__PURE__*/React.createElement("code", null, "billing.invoices"), " found 4,812 records where usage exists and no invoice line was produced."), /*#__PURE__*/React.createElement(Card, {
    padding: "none"
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "12px 16px 0"
    }
  }, /*#__PURE__*/React.createElement(Tabs, {
    value: tab,
    onChange: setTab,
    size: "sm",
    items: [{
      value: "records",
      label: "Differing records",
      count: 4812
    }, {
      value: "logic",
      label: "Logic"
    }, {
      value: "history",
      label: "History"
    }]
  })), tab === "records" ? /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8,
      padding: "12px 16px"
    }
  }, /*#__PURE__*/React.createElement(Tag, {
    icon: "calendar",
    selected: true,
    onRemove: () => {}
  }, "12 Aug 2026"), /*#__PURE__*/React.createElement(Tag, {
    icon: "database"
  }, "billing.usage_daily"), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-xs)",
      color: "var(--ink-400)",
      fontFamily: "var(--font-mono)"
    }
  }, "5 of 4,812 shown"), /*#__PURE__*/React.createElement(Button, {
    size: "sm",
    variant: "ghost",
    iconLeft: "download"
  }, "Export")), /*#__PURE__*/React.createElement(DataTable, {
    dense: true,
    style: {
      border: 0,
      borderRadius: 0,
      borderTop: "1px solid var(--border-subtle)"
    },
    columns: [{
      key: "id",
      header: "Account",
      mono: true,
      width: 100
    }, {
      key: "cust",
      header: "Customer"
    }, {
      key: "usage",
      header: "Usage units",
      align: "right",
      mono: true
    }, {
      key: "billed",
      header: "Billed units",
      align: "right",
      mono: true
    }, {
      key: "delta",
      header: "Difference",
      align: "right",
      mono: true
    }, {
      key: "pct",
      header: "%",
      align: "right",
      width: 84,
      render: r => /*#__PURE__*/React.createElement("span", {
        style: {
          fontFamily: "var(--font-mono)",
          fontSize: "var(--text-sm)",
          color: r.pct === "0.0%" ? "var(--ink-400)" : "var(--status-critical)"
        }
      }, r.pct)
    }],
    rows: DIFFS
  })) : tab === "logic" ? /*#__PURE__*/React.createElement("div", {
    style: {
      padding: 16,
      display: "flex",
      flexDirection: "column",
      gap: 14
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--ink-600)",
      lineHeight: 1.6
    }
  }, "The rule the business defined, in the words the business used."), /*#__PURE__*/React.createElement("div", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-sm)",
      lineHeight: 1.8,
      background: "var(--surface-sunken)",
      border: "1px solid var(--border-subtle)",
      borderRadius: "var(--radius-md)",
      padding: 16,
      color: "var(--ink-800)"
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--signal-reconciliation)"
    }
  }, "compare"), " billing.usage_daily.units"), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--signal-reconciliation)"
    }
  }, "with"), "\xA0\xA0\xA0 billing.invoices.billed_units"), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--signal-reconciliation)"
    }
  }, "by"), "\xA0\xA0\xA0\xA0\xA0 account_id, service_day"), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--signal-incident)"
    }
  }, "raise incident when"), " difference > 0.5%"), /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--ink-400)"
    }
  }, "run"), " hourly from 09:00 UTC")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 16,
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement(Switch, {
    id: "w1",
    checked: true,
    onChange: () => {},
    label: "Watch this reconciliation"
  }), /*#__PURE__*/React.createElement(Switch, {
    id: "w2",
    checked: false,
    onChange: () => {},
    label: "Email me when it fails"
  }))) : /*#__PURE__*/React.createElement("div", {
    style: {
      padding: 16,
      display: "flex",
      flexDirection: "column",
      gap: 0
    }
  }, [["09:14", "Incident raised automatically", "critical"], ["09:00", "Reconciliation run — 4,812 differences", "warning"], ["08:20", "Tariff plan changed on erp.tariffs", "info"], ["Yesterday 09:00", "Reconciliation run — 0 differences", "ok"]].map(([t, d, tone], i) => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      display: "flex",
      gap: 12,
      padding: "11px 0",
      borderBottom: i < 3 ? "1px solid var(--border-subtle)" : "none"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-xs)",
      color: "var(--ink-400)",
      width: 100,
      flex: "none"
    }
  }, t), /*#__PURE__*/React.createElement(StatusDot, {
    tone: tone,
    style: {
      marginTop: 5
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--ink-800)"
    }
  }, d)))))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(CardHeader, {
    title: "Incident",
    description: row ? row.id : "INC-2026-0841"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 12
    }
  }, [["Type", /*#__PURE__*/React.createElement(SignalBadge, {
    type: "incident",
    size: "sm"
  })], ["Severity", /*#__PURE__*/React.createElement(Badge, {
    tone: "critical"
  }, "Critical")], ["Status", /*#__PURE__*/React.createElement(Badge, {
    tone: "warning"
  }, "Open")], ["Owner", /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-sm)"
    }
  }, "Unassigned")], ["Detected", /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-sm)"
    }
  }, "09:14 UTC")], ["Role", /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-sm)"
    }
  }, "Revenue Assurance")]].map(([k, v]) => /*#__PURE__*/React.createElement("div", {
    key: k,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-xs)",
      color: "var(--ink-400)",
      width: 68,
      flex: "none"
    }
  }, k), v)))), /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(CardHeader, {
    title: "Where it came from"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 8
    }
  }, ["billing.usage_daily", "billing.invoices", "mediation.cdr"].map(s => /*#__PURE__*/React.createElement(Tag, {
    key: s,
    icon: "database"
  }, s)))), /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(CardHeader, {
    title: "Related pattern"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "flex-start",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement(SignalBadge, {
    type: "pattern",
    size: "sm"
  })), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--ink-600)",
      lineHeight: 1.55,
      marginTop: 10
    }
  }, "This is the 4th occurrence in 6 months, always within two days of a tariff change."), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 12
    }
  }, /*#__PURE__*/React.createElement(Button, {
    size: "sm",
    variant: "secondary",
    block: true
  }, "Open in Pattern Studio")))));
}
Object.assign(window, {
  IncidentDetail
});
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/product/IncidentDetail.jsx", error: String((e && e.message) || e) }); }

// ui_kits/product/PatternStudio.jsx
try { (() => {
const {
  Card,
  CardHeader,
  Button,
  Tabs,
  Icon,
  Tag,
  Badge,
  SignalBadge,
  DataTable,
  Select,
  Field,
  Input,
  Switch,
  MetricTile
} = window.INTERADesignSystem_430dc5;
const OCCURRENCES = [{
  when: "12 Aug 2026",
  trigger: "Tariff change — Enterprise 500",
  impact: "€48,120",
  lag: "2 days"
}, {
  when: "31 May 2026",
  trigger: "Tariff change — Retail Flex",
  impact: "€21,440",
  lag: "1 day"
}, {
  when: "28 Feb 2026",
  trigger: "Price list published",
  impact: "€36,905",
  lag: "2 days"
}, {
  when: "30 Nov 2025",
  trigger: "Tariff change — Enterprise 500",
  impact: "€19,780",
  lag: "2 days"
}];
function PatternStudio() {
  const [view, setView] = React.useState("timeline");
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: 20,
      display: "grid",
      gridTemplateColumns: "300px minmax(0,1fr)",
      gap: 16,
      alignItems: "start",
      overflowY: "auto"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(CardHeader, {
    title: "Conditions",
    description: "What has to be true for the pattern to hold."
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 14
    }
  }, /*#__PURE__*/React.createElement(Field, {
    label: "When this happens",
    htmlFor: "p1"
  }, /*#__PURE__*/React.createElement(Select, {
    id: "p1",
    size: "sm",
    options: ["Tariff plan changed", "Price list published", "Contract amended"]
  })), /*#__PURE__*/React.createElement(Field, {
    label: "In this source",
    htmlFor: "p2"
  }, /*#__PURE__*/React.createElement(Select, {
    id: "p2",
    size: "sm",
    options: ["erp.tariffs", "erp.pricelists", "crm.contracts"]
  })), /*#__PURE__*/React.createElement(Field, {
    label: "Within",
    htmlFor: "p3"
  }, /*#__PURE__*/React.createElement(Input, {
    id: "p3",
    size: "sm",
    mono: true,
    defaultValue: "3 days"
  })), /*#__PURE__*/React.createElement(Field, {
    label: "Then look for",
    htmlFor: "p4"
  }, /*#__PURE__*/React.createElement(Select, {
    id: "p4",
    size: "sm",
    options: ["Unbilled usage above threshold", "Invoice run incomplete", "Provisioning mismatch"]
  })), /*#__PURE__*/React.createElement(Switch, {
    id: "p5",
    checked: true,
    onChange: () => {},
    label: "Raise an incident on match"
  }), /*#__PURE__*/React.createElement(Button, {
    size: "sm",
    block: true
  }, "Save pattern"))), /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(CardHeader, {
    title: "Applies to"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexWrap: "wrap",
      gap: 7
    }
  }, ["Revenue Assurance", "Finance Control"].map(r => /*#__PURE__*/React.createElement(Tag, {
    key: r,
    selected: true
  }, r))))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 12,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "flex-start",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 9
    }
  }, /*#__PURE__*/React.createElement(SignalBadge, {
    type: "pattern",
    size: "sm"
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-xs)",
      color: "var(--ink-400)"
    }
  }, "PAT-118"), /*#__PURE__*/React.createElement(Badge, {
    tone: "ok"
  }, "Confirmed")), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xl)",
      fontWeight: 600,
      letterSpacing: "-0.01em",
      marginTop: 8
    }
  }, "Unbilled usage follows a tariff change"), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-md)",
      color: "var(--ink-600)",
      lineHeight: 1.6,
      marginTop: 6,
      maxWidth: 620
    }
  }, "Every time a tariff plan is changed on ", /*#__PURE__*/React.createElement("code", null, "erp.tariffs"), ", usage for affected accounts stops being billed for one to two days.")), /*#__PURE__*/React.createElement(Button, {
    variant: "secondary",
    size: "sm",
    iconLeft: "share-2"
  }, "Share"))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(4,minmax(0,1fr))",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement(MetricTile, {
    label: "Occurrences",
    value: "4",
    note: "in 6 months"
  }), /*#__PURE__*/React.createElement(MetricTile, {
    label: "Total impact",
    value: "\u20AC126,245",
    tone: "warning",
    delta: "+38%",
    direction: "up",
    note: "vs prior period"
  }), /*#__PURE__*/React.createElement(MetricTile, {
    label: "Median lag",
    value: "2",
    unit: "days",
    note: "after trigger"
  }), /*#__PURE__*/React.createElement(MetricTile, {
    label: "Confidence",
    value: "92",
    unit: "%",
    tone: "ok",
    note: "matched conditions"
  })), /*#__PURE__*/React.createElement(Card, {
    padding: "none"
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      padding: "12px 16px",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement(Tabs, {
    variant: "segmented",
    size: "sm",
    value: view,
    onChange: setView,
    items: [{
      value: "timeline",
      label: "Timeline"
    }, {
      value: "table",
      label: "Occurrences"
    }]
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-xs)",
      color: "var(--ink-400)"
    }
  }, "Last 6 months")), view === "timeline" ? /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "8px 20px 26px",
      borderTop: "1px solid var(--border-subtle)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      height: 130,
      marginTop: 22
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      right: 0,
      top: 62,
      height: 1,
      background: "var(--border-default)"
    }
  }), OCCURRENCES.slice().reverse().map((o, i) => {
    const left = `${8 + i * 28}%`;
    const h = [42, 78, 50, 96][i];
    return /*#__PURE__*/React.createElement("div", {
      key: o.when,
      style: {
        position: "absolute",
        left,
        top: 0,
        transform: "translateX(-50%)",
        textAlign: "center"
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        display: "flex",
        alignItems: "flex-end",
        justifyContent: "center",
        height: 62
      }
    }, /*#__PURE__*/React.createElement("div", {
      style: {
        width: 30,
        height: h,
        background: "var(--violet-100)",
        borderTop: "3px solid var(--signal-pattern)",
        borderRadius: "2px 2px 0 0"
      }
    })), /*#__PURE__*/React.createElement("div", {
      style: {
        width: 11,
        height: 11,
        borderRadius: 999,
        background: "var(--signal-incident)",
        border: "2px solid var(--white)",
        margin: "-6px auto 0",
        position: "relative"
      }
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        fontFamily: "var(--font-mono)",
        fontSize: 10,
        color: "var(--ink-500)",
        marginTop: 8
      }
    }, o.when), /*#__PURE__*/React.createElement("div", {
      style: {
        fontSize: 10,
        color: "var(--ink-400)",
        marginTop: 2
      }
    }, o.impact));
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 20,
      marginTop: 6,
      fontSize: "var(--text-xs)",
      color: "var(--ink-500)"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 6
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 10,
      height: 10,
      background: "var(--violet-100)",
      borderTop: "3px solid var(--signal-pattern)"
    }
  }), " Impact"), /*#__PURE__*/React.createElement("span", {
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 6
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 9,
      height: 9,
      borderRadius: 999,
      background: "var(--signal-incident)"
    }
  }), " Incident raised"))) : /*#__PURE__*/React.createElement(DataTable, {
    dense: true,
    style: {
      border: 0,
      borderRadius: 0,
      borderTop: "1px solid var(--border-subtle)"
    },
    columns: [{
      key: "when",
      header: "Date",
      mono: true,
      width: 130
    }, {
      key: "trigger",
      header: "Trigger"
    }, {
      key: "lag",
      header: "Lag",
      mono: true,
      width: 90
    }, {
      key: "impact",
      header: "Impact",
      align: "right",
      mono: true,
      width: 110
    }],
    rows: OCCURRENCES
  }))));
}
Object.assign(window, {
  PatternStudio
});
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/product/PatternStudio.jsx", error: String((e && e.message) || e) }); }

// ui_kits/product/RoleDashboard.jsx
try { (() => {
const {
  MetricTile,
  DataTable,
  SignalBadge,
  Badge,
  Card,
  CardHeader,
  Button,
  Tabs,
  Alert,
  Icon,
  StatusDot,
  Tag
} = window.INTERADesignSystem_430dc5;
const ROWS = [{
  id: "INC-2026-0841",
  type: "incident",
  name: "Usage not billed — 12 Aug",
  src: "billing.usage_daily",
  diff: "€48,120",
  when: "09:14",
  st: "Open",
  tone: "critical"
}, {
  id: "REC-4412",
  type: "reconciliation",
  name: "Billing vs mediation volumes",
  src: "mediation.cdr",
  diff: "4,812",
  when: "09:00",
  st: "Investigating",
  tone: "warning"
}, {
  id: "INC-2026-0840",
  type: "incident",
  name: "Invoice run incomplete",
  src: "billing.invoices",
  diff: "212",
  when: "08:41",
  st: "Open",
  tone: "critical"
}, {
  id: "EVT-88213",
  type: "event",
  name: "Tariff plan changed — Enterprise 500",
  src: "erp.tariffs",
  diff: "—",
  when: "08:20",
  st: "Noted",
  tone: "neutral"
}, {
  id: "PAT-118",
  type: "pattern",
  name: "Month-end exception spike",
  src: "ops.exceptions",
  diff: "×3.1",
  when: "Yesterday",
  st: "Confirmed",
  tone: "ok"
}, {
  id: "REC-4409",
  type: "reconciliation",
  name: "CRM vs ERP customer count",
  src: "crm.accounts",
  diff: "118",
  when: "Yesterday",
  st: "Resolved",
  tone: "ok"
}, {
  id: "INC-2026-0838",
  type: "incident",
  name: "Connector stopped responding",
  src: "erp.connector",
  diff: "—",
  when: "Yesterday",
  st: "Open",
  tone: "critical"
}, {
  id: "EVT-88190",
  type: "event",
  name: "New price list published",
  src: "erp.pricelists",
  diff: "—",
  when: "2 days ago",
  st: "Noted",
  tone: "neutral"
}];
function RoleDashboard({
  onOpenRow
}) {
  const [tab, setTab] = React.useState("all");
  const rows = tab === "all" ? ROWS : ROWS.filter(r => r.type === tab);
  return /*#__PURE__*/React.createElement("div", {
    style: {
      padding: 20,
      display: "flex",
      flexDirection: "column",
      gap: 16,
      overflowY: "auto"
    }
  }, /*#__PURE__*/React.createElement(Alert, {
    tone: "warning",
    title: "Billing and usage do not agree for 12 August",
    action: /*#__PURE__*/React.createElement(Button, {
      size: "sm",
      variant: "secondary",
      onClick: () => onOpenRow(ROWS[0])
    }, "Open incident")
  }, "4,812 records differ by more than the 0.5% threshold. Difference \u20AC48,120."), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(4,minmax(0,1fr))",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement(MetricTile, {
    label: "Unbilled usage",
    value: "\u20AC48,120",
    delta: "+12.4%",
    direction: "up",
    tone: "warning",
    note: "vs last week"
  }), /*#__PURE__*/React.createElement(MetricTile, {
    label: "Reconciled records",
    value: "128,904",
    delta: "+0.3%",
    direction: "up",
    tone: "ok",
    note: "today"
  }), /*#__PURE__*/React.createElement(MetricTile, {
    label: "Open incidents",
    value: "3",
    delta: "+2",
    direction: "up",
    tone: "critical",
    note: "last 24h"
  }), /*#__PURE__*/React.createElement(MetricTile, {
    label: "Revenue coverage",
    value: "94.2",
    unit: "%",
    delta: "0.0%",
    direction: "flat",
    note: "of billed revenue"
  })), /*#__PURE__*/React.createElement(Card, {
    padding: "none"
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "12px 16px 0"
    }
  }, /*#__PURE__*/React.createElement(Tabs, {
    value: tab,
    onChange: setTab,
    size: "sm",
    items: [{
      value: "all",
      label: "Everything",
      count: ROWS.length
    }, {
      value: "incident",
      label: "Incidents",
      icon: "alert-triangle",
      count: 3
    }, {
      value: "reconciliation",
      label: "Reconciliations",
      icon: "scale",
      count: 2
    }, {
      value: "event",
      label: "Events",
      icon: "activity",
      count: 2
    }, {
      value: "pattern",
      label: "Patterns",
      icon: "git-branch",
      count: 1
    }]
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8,
      padding: "12px 16px"
    }
  }, /*#__PURE__*/React.createElement(Tag, {
    icon: "filter",
    selected: true,
    onRemove: () => {}
  }, "last 7 days"), /*#__PURE__*/React.createElement(Tag, {
    icon: "database"
  }, "billing.*"), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1
    }
  }), /*#__PURE__*/React.createElement(Button, {
    size: "sm",
    variant: "ghost",
    iconLeft: "download"
  }, "Export"), /*#__PURE__*/React.createElement(Button, {
    size: "sm",
    variant: "secondary",
    iconLeft: "plus"
  }, "New reconciliation")), /*#__PURE__*/React.createElement(DataTable, {
    dense: true,
    onRowClick: onOpenRow,
    style: {
      border: 0,
      borderRadius: 0,
      borderTop: "1px solid var(--border-subtle)"
    },
    columns: [{
      key: "type",
      header: "Type",
      width: 138,
      render: r => /*#__PURE__*/React.createElement(SignalBadge, {
        type: r.type,
        size: "sm"
      })
    }, {
      key: "name",
      header: "Subject"
    }, {
      key: "src",
      header: "Source",
      mono: true,
      width: 170
    }, {
      key: "diff",
      header: "Difference",
      align: "right",
      mono: true,
      width: 110,
      sorted: "desc"
    }, {
      key: "when",
      header: "Detected",
      mono: true,
      width: 96
    }, {
      key: "st",
      header: "Status",
      width: 124,
      render: r => /*#__PURE__*/React.createElement(Badge, {
        tone: r.tone
      }, r.st)
    }],
    rows: rows
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "minmax(0,1fr) minmax(0,1fr)",
      gap: 12
    }
  }, /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(CardHeader, {
    title: "Watched reconciliations",
    description: "Run hourly from 09:00",
    action: /*#__PURE__*/React.createElement(Button, {
      variant: "link",
      size: "sm"
    }, "Manage")
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 10
    }
  }, [["Billing vs mediation volumes", "warning", "4,812 differ"], ["Invoices vs payments", "ok", "matched"], ["CRM vs ERP customers", "ok", "matched"], ["Provisioned vs billed services", "warning", "118 differ"]].map(([n, t, s]) => /*#__PURE__*/React.createElement("div", {
    key: n,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      fontSize: "var(--text-sm)"
    }
  }, /*#__PURE__*/React.createElement(StatusDot, {
    tone: t
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      flex: 1,
      color: "var(--ink-800)"
    }
  }, n), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-xs)",
      color: "var(--ink-500)"
    }
  }, s))))), /*#__PURE__*/React.createElement(Card, null, /*#__PURE__*/React.createElement(CardHeader, {
    title: "Connected systems",
    description: "Read-only. Nothing is written back.",
    action: /*#__PURE__*/React.createElement(Button, {
      variant: "link",
      size: "sm"
    }, "Add")
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexWrap: "wrap",
      gap: 8
    }
  }, ["erp.orders", "crm.accounts", "billing.invoices", "billing.usage_daily", "mediation.cdr", "ops_checks.xlsx"].map(t => /*#__PURE__*/React.createElement(Tag, {
    key: t,
    icon: "database"
  }, t))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8,
      marginTop: 16,
      fontSize: "var(--text-xs)",
      color: "var(--ink-500)"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "clock",
    size: 13
  }), " Last sync 09:14 UTC \xB7 median latency 1.8s"))));
}
Object.assign(window, {
  RoleDashboard,
  ROWS
});
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/product/RoleDashboard.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/HeroSections.jsx
try { (() => {
const {
  Button,
  Icon,
  Tag,
  Card,
  SignalChain,
  Badge,
  SignalBadge
} = window.INTERADesignSystem_430dc5;
function HeroFloatCard({
  show = true
}) {
  if (!show) return null;
  return /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      right: "max(-76px, -6%)",
      bottom: -56,
      width: 262,
      background: "var(--surface-card)",
      border: "1px solid var(--border-default)",
      borderTop: "3px solid var(--signal-incident)",
      borderRadius: "var(--radius-card)",
      boxShadow: "var(--shadow-overlay)",
      padding: 16
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8
    }
  }, /*#__PURE__*/React.createElement(SignalBadge, {
    type: "incident",
    size: "sm"
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-2xs)",
      color: "var(--ink-400)"
    }
  }, "09:14 UTC")), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-md)",
      fontWeight: 600,
      color: "var(--ink-900)",
      marginTop: 10,
      lineHeight: 1.4
    }
  }, "Usage recorded but not billed"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "baseline",
      gap: 8,
      marginTop: 10
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-2xl)",
      fontWeight: 500,
      color: "var(--ink-900)",
      letterSpacing: "-0.01em"
    }
  }, "\u20AC48,120"), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-xs)",
      color: "var(--ink-500)"
    }
  }, "across 4,812 records")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 7,
      marginTop: 12,
      paddingTop: 12,
      borderTop: "1px solid var(--border-subtle)",
      fontSize: "var(--text-xs)",
      color: "var(--ink-500)"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "git-branch",
    size: 13,
    color: "var(--signal-pattern)"
  }), "4th occurrence \u2014 always after a tariff change"));
}

/** Background graphic: the mark, drawn very large as a hairline outline. */
function HeroBackdrop() {
  return /*#__PURE__*/React.createElement("div", {
    "aria-hidden": "true",
    style: {
      position: "absolute",
      inset: 0,
      pointerEvents: "none",
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      inset: 0,
      maxWidth: 1160,
      margin: "0 auto"
    }
  }, [0, 25, 50, 75, 100].map(p => /*#__PURE__*/React.createElement("div", {
    key: p,
    style: {
      position: "absolute",
      top: 0,
      bottom: 0,
      left: `${p}%`,
      width: 1,
      background: "rgba(255,255,255,.07)"
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      right: -260,
      top: "50%",
      transform: "translateY(-50%)",
      width: 1180,
      height: 1180,
      background: "radial-gradient(circle, var(--wash-blue-dark) 0%, transparent 62%)"
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      right: -60,
      bottom: -360,
      width: 820,
      height: 820,
      background: "radial-gradient(circle, var(--wash-teal-dark) 0%, transparent 62%)"
    }
  }), /*#__PURE__*/React.createElement("svg", {
    viewBox: "0 0 40 40",
    width: 760,
    height: 760,
    fill: "none",
    style: {
      position: "absolute",
      right: -190,
      top: -168
    }
  }, /*#__PURE__*/React.createElement("rect", {
    x: "5",
    y: "5",
    width: "22",
    height: "22",
    rx: "3.5",
    stroke: "rgba(255,255,255,.075)",
    strokeWidth: "0.5"
  }), /*#__PURE__*/React.createElement("rect", {
    x: "13",
    y: "13",
    width: "14",
    height: "14",
    fill: "rgba(255,255,255,.028)"
  }), /*#__PURE__*/React.createElement("rect", {
    x: "13",
    y: "13",
    width: "22",
    height: "22",
    rx: "3.5",
    stroke: "rgba(255,255,255,.075)",
    strokeWidth: "0.5"
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "absolute",
      left: 0,
      right: 0,
      bottom: 0,
      height: 1,
      background: "rgba(255,255,255,.12)"
    }
  }));
}
function Hero({
  onNavigate
}) {
  const [colRef, colWidth] = useElementWidth();
  const proof = [["route-off", "No migration"], ["lock", "Read-only access"], ["circle-dot", "Start with one role"]];
  return /*#__PURE__*/React.createElement("div", {
    id: "top",
    style: {
      position: "relative",
      overflow: "hidden",
      background: "var(--ink-950)"
    }
  }, /*#__PURE__*/React.createElement(HeroBackdrop, null), /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      maxWidth: 1160,
      margin: "0 auto",
      padding: "96px 32px 100px",
      display: "grid",
      gridTemplateColumns: "minmax(360px,1fr) minmax(420px,520px)",
      gap: 56,
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 9,
      border: "1px solid rgba(255,255,255,.22)",
      borderRadius: "var(--radius-round)",
      padding: "5px 14px 5px 10px",
      background: "rgba(255,255,255,.06)"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 7,
      height: 7,
      borderRadius: 999,
      background: "var(--teal-500)"
    }
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-xs)",
      color: "rgba(255,255,255,.82)",
      fontWeight: 500
    }
  }, "In beta \u2014 Early Adopter programme open")), /*#__PURE__*/React.createElement("h1", {
    style: {
      fontSize: "clamp(38px,4vw,56px)",
      fontWeight: 600,
      lineHeight: 1.08,
      letterSpacing: "-0.028em",
      color: "var(--white)",
      marginTop: 26,
      maxWidth: 520,
      textWrap: "balance"
    }
  }, "See what needs attention. Before someone has to ask."), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-lg)",
      lineHeight: 1.6,
      color: "rgba(255,255,255,.66)",
      marginTop: 24,
      maxWidth: 440
    }
  }, "INTERA connects the systems your teams already use and gives each role a clear view of what matters \u2014 changes, risks, inconsistencies and trends."), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexWrap: "wrap",
      gap: 12,
      marginTop: 34
    }
  }, /*#__PURE__*/React.createElement(Button, {
    size: "lg",
    variant: "inverse",
    onClick: () => onNavigate("early")
  }, "Get Early Access"), /*#__PURE__*/React.createElement(Button, {
    size: "lg",
    variant: "outlineInverse",
    iconRight: "arrow-right",
    onClick: () => onNavigate("how")
  }, "See how INTERA works")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 28,
      marginTop: 40,
      paddingTop: 22,
      borderTop: "1px solid rgba(255,255,255,.14)",
      flexWrap: "wrap"
    }
  }, proof.map(([ic, t]) => /*#__PURE__*/React.createElement("span", {
    key: t,
    style: {
      display: "inline-flex",
      alignItems: "center",
      gap: 8,
      fontSize: "var(--text-sm)",
      color: "rgba(255,255,255,.78)"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: ic,
    size: 15,
    color: "rgba(255,255,255,.45)"
  }), t))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      marginTop: 20,
      flexWrap: "wrap"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-2xs)",
      fontWeight: 600,
      letterSpacing: "0.09em",
      textTransform: "uppercase",
      color: "rgba(255,255,255,.4)"
    }
  }, "Reads from"), /*#__PURE__*/React.createElement("span", {
    style: {
      display: "flex",
      gap: 14,
      flexWrap: "wrap"
    }
  }, ["ERP", "CRM", "Billing", "Excel", "Internal tools"].map(t => /*#__PURE__*/React.createElement("span", {
    key: t,
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-xs)",
      color: "rgba(255,255,255,.58)"
    }
  }, t))))), /*#__PURE__*/React.createElement("div", {
    ref: colRef,
    style: {
      position: "relative"
    }
  }, /*#__PURE__*/React.createElement(ProductFrame, {
    title: "Revenue Assurance",
    scale: 0.52,
    designWidth: 1000,
    contentHeight: 458,
    onDark: true
  }, /*#__PURE__*/React.createElement(DashboardPreview, null)), /*#__PURE__*/React.createElement(HeroFloatCard, {
    show: colWidth >= 500
  }))));
}
function Problem() {
  const systems = [["ERP", "erp.orders", "boxes"], ["CRM", "crm.accounts", "contact"], ["Billing", "billing.invoices", "receipt"], ["Spreadsheets", "ops_checks.xlsx", "table-2"], ["Internal tools", "provisioning.api", "terminal"]];
  return /*#__PURE__*/React.createElement(Section, {
    tone: "sunken",
    pad: 88,
    washes: [{
      color: "var(--wash-amber)",
      x: "88%",
      y: "18%",
      size: 820
    }]
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "minmax(0,1fr) minmax(0,1fr)",
      gap: 64,
      alignItems: "start"
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement(SectionHead, {
    eyebrow: "The problem",
    title: "This will feel familiar",
    lead: "Your business runs across several systems. Finance sees one part. Operations sees another."
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 14,
      maxWidth: 520,
      marginTop: -18
    }
  }, /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-base)",
      lineHeight: 1.65,
      color: "var(--ink-700)"
    }
  }, "CRM, billing, ERP, spreadsheets and internal tools each contain pieces of the picture. Problems often become visible only when someone connects those pieces manually."), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-base)",
      lineHeight: 1.65,
      color: "var(--ink-700)"
    }
  }, "Teams spend time checking, reconciling, explaining and preparing information that already exists somewhere in the business."), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-base)",
      lineHeight: 1.65,
      color: "var(--ink-900)",
      fontWeight: 500
    }
  }, "INTERA makes that operating picture continuously visible."))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 10
    }
  }, systems.map(([n, id, ic], i) => /*#__PURE__*/React.createElement("div", {
    key: n,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 14,
      background: "var(--white)",
      border: "1px solid var(--border-subtle)",
      borderRadius: "var(--radius-md)",
      padding: "13px 16px",
      marginLeft: [0, 26, 52, 26, 0][i],
      boxShadow: "var(--shadow-xs)"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: ic,
    size: 16,
    color: "var(--ink-500)"
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-md)",
      fontWeight: 500,
      color: "var(--ink-800)"
    }
  }, n), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-xs)",
      color: "var(--ink-400)",
      marginLeft: "auto"
    }
  }, id), /*#__PURE__*/React.createElement("span", {
    style: {
      width: 8,
      height: 8,
      borderRadius: 999,
      background: i === 2 ? "var(--status-warning)" : "var(--ink-200)"
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      marginTop: 8,
      color: "var(--ink-400)",
      fontSize: "var(--text-xs)"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "corner-down-right",
    size: 15
  }), " Pieces of the same picture, checked by hand."))));
}
function HowItWorks() {
  const steps = [["plug", "Connect your existing systems", "INTERA connects to finance, operations, CRM, billing, ERP, Excel and internal systems without replacing them."], ["brain-circuit", "INTERA understands what matters", "It applies business logic and watches changes, risks and inconsistencies."], ["eye", "See what needs attention", "Managers immediately see what changed, what requires action and where to investigate."]];
  return /*#__PURE__*/React.createElement(Section, {
    id: "how",
    pad: 96
  }, /*#__PURE__*/React.createElement(SectionHead, {
    eyebrow: "How it works",
    title: "Get full visibility without changing how your company operates"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(3,minmax(0,1fr))",
      gap: 20
    }
  }, steps.map(([ic, t, d], i) => /*#__PURE__*/React.createElement(Card, {
    key: t,
    padding: "loose"
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 12,
      marginBottom: 16
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      width: 34,
      height: 34,
      borderRadius: "var(--radius-md)",
      background: "var(--blue-50)",
      border: "1px solid var(--blue-100)",
      display: "grid",
      placeItems: "center",
      color: "var(--blue-600)"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: ic,
    size: 17
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-xs)",
      color: "var(--ink-400)"
    }
  }, "0", i + 1)), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xl)",
      fontWeight: 600,
      letterSpacing: "-0.01em",
      lineHeight: 1.3
    }
  }, t), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-md)",
      lineHeight: 1.6,
      color: "var(--ink-600)",
      marginTop: 10
    }
  }, d)))), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-lg)",
      color: "var(--ink-700)",
      marginTop: 36,
      maxWidth: 760,
      lineHeight: 1.6
    }
  }, "INTERA doesn't replace your team \u2014 it removes unnecessary manual checking and reporting between systems and people."));
}
function Champion() {
  const items = [["bell", "Know before you're asked", "See problems and unusual changes before they become questions or escalations."], ["clock", "Spend less time proving what's happening", "Reduce repetitive reporting, manual checks and status updates."], ["clipboard-check", "Bring problems with answers", "See the supporting data and understand what requires action."], ["shield-check", "Show that your area is under control", "Give management clear and consistent visibility without preparing another spreadsheet."], ["repeat", "Make improvements that last", "Turn the checks, knowledge and working practices your team already uses into something repeatable and visible across the organization."]];
  return /*#__PURE__*/React.createElement(Section, {
    tone: "sunken",
    pad: 88,
    washes: [{
      color: "var(--wash-teal)",
      x: "82%",
      y: "78%",
      size: 880
    }]
  }, /*#__PURE__*/React.createElement(SectionHead, {
    eyebrow: "For the manager who owns the area",
    title: "Make your area easier to run",
    lead: "INTERA doesn't just give management more visibility. It helps you stay on top of the part of the business you're responsible for."
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(3,minmax(0,1fr))",
      gap: 20
    }
  }, items.map(([ic, t, d]) => /*#__PURE__*/React.createElement(Card, {
    key: t
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      color: "var(--teal-600)",
      marginBottom: 12
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: ic,
    size: 20
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-md)",
      fontWeight: 600,
      lineHeight: 1.4
    }
  }, t), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-sm)",
      lineHeight: 1.6,
      color: "var(--ink-600)",
      marginTop: 8
    }
  }, d))), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      padding: "0 8px"
    }
  }, /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-xl)",
      fontWeight: 600,
      lineHeight: 1.35,
      letterSpacing: "-0.01em",
      color: "var(--ink-900)"
    }
  }, "Less chasing.", /*#__PURE__*/React.createElement("br", null), "Fewer surprises.", /*#__PURE__*/React.createElement("br", null), "More confidence in the part of the business you own."))));
}
function InAction() {
  return /*#__PURE__*/React.createElement(Section, {
    id: "action",
    pad: 96
  }, /*#__PURE__*/React.createElement(SectionHead, {
    eyebrow: "INTERA in action",
    title: "Don't just watch the business. Catch what matters."
  }), /*#__PURE__*/React.createElement(SignalChain, {
    captions: {
      event: "Something important changed.",
      reconciliation: "Things that should agree — don't.",
      incident: "Something requires attention and action.",
      pattern: "Understand what keeps happening, and under which conditions."
    }
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 40,
      borderLeft: "3px solid var(--blue-600)",
      paddingLeft: 24,
      maxWidth: 820
    }
  }, /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-2xl)",
      lineHeight: 1.4,
      letterSpacing: "-0.01em",
      color: "var(--ink-900)"
    }
  }, "From \"something looks wrong\" to \"we know what is happening, why it matters, and what to watch next.\"")));
}
Object.assign(window, {
  Hero,
  HeroFloatCard,
  HeroBackdrop,
  Problem,
  HowItWorks,
  Champion,
  InAction
});
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/HeroSections.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/OfferSections.jsx
try { (() => {
const {
  Button,
  Icon,
  Card,
  Badge,
  Tag,
  Field,
  Input,
  Textarea,
  Select,
  Checkbox
} = window.INTERADesignSystem_430dc5;
const ROLES = [["circle-dollar-sign", "Finance Control", "Margins, accruals and cost movements that don't match the plan.", "var(--signal-event)", "var(--signal-event-line)"], ["gauge", "Operations Oversight", "Throughput, exceptions and processes that quietly stopped working.", "var(--signal-reconciliation)", "var(--signal-reconciliation-line)"], ["scale", "Revenue Assurance", "Usage, billing and payments that should agree — and don't.", "var(--signal-incident)", "var(--signal-incident-line)"], ["heart-pulse", "Customer Health", "Accounts changing behaviour before they change supplier.", "var(--signal-pattern)", "var(--signal-pattern-line)"], ["shield-check", "System Integrity", "Data, interfaces and connectors that drift out of line.", "var(--status-ok)", "var(--status-ok-line)"]];
function Roles() {
  return /*#__PURE__*/React.createElement(Section, {
    id: "roles",
    tone: "sunken",
    pad: 88,
    washes: [{
      color: "var(--wash-blue)",
      x: "18%",
      y: "22%",
      size: 900
    }, {
      color: "var(--wash-violet)",
      x: "88%",
      y: "82%",
      size: 760
    }]
  }, /*#__PURE__*/React.createElement(SectionHead, {
    eyebrow: "INTERA Roles",
    title: "Pre-built visibility for the parts of your business that matter most",
    lead: "Intera Roles are ready-made business modules designed around real responsibilities. Each role comes with predefined metrics, logic and automatic issue detection, so you can see what's happening without building anything from scratch.",
    maxWidth: 820
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(5,minmax(0,1fr))",
      gap: 14
    }
  }, ROLES.map(([ic, t, d, c, line]) => /*#__PURE__*/React.createElement(Card, {
    key: t,
    accent: c,
    accentLine: line,
    interactive: true,
    padding: "compact"
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      color: c,
      marginBottom: 12,
      marginTop: 4
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: ic,
    size: 20
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-md)",
      fontWeight: 600,
      lineHeight: 1.35
    }
  }, t), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-sm)",
      lineHeight: 1.55,
      color: "var(--ink-600)",
      marginTop: 8
    }
  }, d)))), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-xl)",
      fontWeight: 600,
      color: "var(--ink-900)",
      marginTop: 36,
      letterSpacing: "-0.01em"
    }
  }, "Different responsibilities. One operating picture."));
}
function ExistingIT() {
  const points = ["ERP, CRM, billing and others remain as your systems of record", "INTERA connects to them, never replacing anything", "IT is responsible for access to systems and data", "Business decides which Metrics, Events, Incidents, Reconciliations and Patterns are important", "No company-wide transformation project"];
  return /*#__PURE__*/React.createElement(Section, {
    pad: 88
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "minmax(0,1fr) minmax(0,1.1fr)",
      gap: 64,
      alignItems: "start"
    }
  }, /*#__PURE__*/React.createElement(SectionHead, {
    eyebrow: "Working with existing IT",
    title: "Your systems stay. INTERA makes them more useful."
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 2,
      marginTop: 4
    }
  }, points.map(p => /*#__PURE__*/React.createElement("div", {
    key: p,
    style: {
      display: "flex",
      gap: 12,
      padding: "13px 0",
      borderBottom: "1px solid var(--border-subtle)"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--status-ok)",
      marginTop: 2
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "check",
    size: 17,
    strokeWidth: 2
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-md)",
      lineHeight: 1.55,
      color: "var(--ink-700)"
    }
  }, p))), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-md)",
      lineHeight: 1.65,
      color: "var(--ink-600)",
      marginTop: 22
    }
  }, "Business teams know what they need to control. IT knows how the systems work. INTERA gives them a practical place to meet."))));
}
function StartSmall({
  onSubmit,
  submitted
}) {
  const [optIn, setOptIn] = React.useState(true);
  const examples = ["billing and usage do not correspond", "problem is detected too late", "the same exceptions are constantly manually checked", "manager is gathering data from several different systems"];
  return /*#__PURE__*/React.createElement(Section, {
    tone: "sunken",
    pad: 88,
    washes: [{
      color: "var(--wash-amber)",
      x: "14%",
      y: "80%",
      size: 780
    }]
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "minmax(0,1fr) minmax(0,420px)",
      gap: 64,
      alignItems: "start"
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement(SectionHead, {
    eyebrow: "Start small",
    title: "Start with one real problem",
    lead: "Do not start by implementing INTERA in your whole company. You can start with one role, one operational problem, one working result."
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xs)",
      fontWeight: 600,
      letterSpacing: "0.09em",
      textTransform: "uppercase",
      color: "var(--ink-500)",
      marginTop: -20,
      marginBottom: 14
    }
  }, "For example"), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 9
    }
  }, examples.map(e => /*#__PURE__*/React.createElement("div", {
    key: e,
    style: {
      display: "flex",
      gap: 11,
      alignItems: "flex-start"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--signal-incident)",
      marginTop: 3
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "alert-triangle",
    size: 15
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-md)",
      color: "var(--ink-700)",
      lineHeight: 1.5
    }
  }, e))))), /*#__PURE__*/React.createElement(Card, {
    padding: "loose",
    elevated: true
  }, submitted ? /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 10,
      alignItems: "flex-start",
      padding: "16px 0"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--status-ok)"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "check-circle",
    size: 26
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-lg)",
      fontWeight: 600
    }
  }, "Thank you \u2014 we'll be in touch."), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-md)",
      color: "var(--ink-600)",
      lineHeight: 1.6
    }
  }, "We read every case ourselves. Expect a reply within one business day.")) : /*#__PURE__*/React.createElement("form", {
    onSubmit: e => {
      e.preventDefault();
      onSubmit();
    },
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 16
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-lg)",
      fontWeight: 600,
      letterSpacing: "-0.01em"
    }
  }, "Bring us a real problem"), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--ink-600)",
      marginTop: 5,
      lineHeight: 1.55
    }
  }, "Tell us what you check by hand today. We'll show you what INTERA would catch.")), /*#__PURE__*/React.createElement(Field, {
    label: "Work email",
    htmlFor: "we",
    required: true
  }, /*#__PURE__*/React.createElement(Input, {
    id: "we",
    type: "email",
    placeholder: "name@company.com"
  })), /*#__PURE__*/React.createElement(Field, {
    label: "Which area do you run?",
    htmlFor: "wr"
  }, /*#__PURE__*/React.createElement(Select, {
    id: "wr",
    placeholder: "Choose a role",
    options: ROLES.map(r => r[1])
  })), /*#__PURE__*/React.createElement(Field, {
    label: "The problem",
    htmlFor: "wp"
  }, /*#__PURE__*/React.createElement(Textarea, {
    id: "wp",
    rows: 3,
    placeholder: "Billing and usage do not correspond\u2026"
  })), /*#__PURE__*/React.createElement(Checkbox, {
    id: "wc",
    label: "I'd like to hear about the Early Adopter programme",
    checked: optIn,
    onChange: () => setOptIn(!optIn)
  }), /*#__PURE__*/React.createElement(Button, {
    type: "submit",
    block: true,
    size: "lg"
  }, "Bring us a real problem")))));
}
const TIERS = [{
  name: "Free",
  note: "For evaluation",
  price: "€0",
  features: ["Up to 3 Roles", "Up to 10 users", "Up to 3 integrations", "30-day history", "Local installation"],
  cta: "Try INTERA",
  variant: "secondary"
}, {
  name: "Early Adopter",
  note: "For beta partners",
  price: "Free",
  sub: "for the first 12 months",
  features: ["Unlimited Roles", "Up to 25 users", "Up to 5 integrations", "Unlimited history", "Custom onboarding", "Priority support", "One market package included"],
  cta: "Become an Early Adopter",
  variant: "primary",
  featured: true
}, {
  name: "Commercial",
  note: "For production use",
  price: "€750",
  sub: "/ year — Basic",
  features: ["Corporate — from €4,500/year", "Enterprise — from €15,000/year", "Implementation & onboarding from €4,500", "Custom integrations quoted separately"],
  cta: "Talk to us about your deployment",
  variant: "secondary"
}];
function Pricing({
  onNavigate
}) {
  return /*#__PURE__*/React.createElement(Section, {
    id: "pricing",
    pad: 96
  }, /*#__PURE__*/React.createElement(SectionHead, {
    eyebrow: "Pricing",
    title: "Start free. Pay when INTERA is doing real work.",
    align: "center"
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(3,minmax(0,1fr))",
      gap: 20,
      alignItems: "start"
    }
  }, TIERS.map(t => /*#__PURE__*/React.createElement(Card, {
    key: t.name,
    padding: "loose",
    elevated: t.featured,
    accent: t.featured ? "var(--action-primary)" : undefined,
    accentLine: t.featured ? "var(--blue-200)" : undefined,
    style: t.featured ? {
      transform: "translateY(-8px)"
    } : undefined
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-xs)",
      fontWeight: 600,
      letterSpacing: "0.09em",
      textTransform: "uppercase",
      color: t.featured ? "var(--blue-600)" : "var(--ink-500)"
    }
  }, t.name), t.featured ? /*#__PURE__*/React.createElement(Badge, {
    tone: "info"
  }, "Recommended") : null), /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--ink-500)",
      marginTop: 4
    }
  }, t.note), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "baseline",
      gap: 7,
      marginTop: 18
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: "var(--text-4xl)",
      fontWeight: 500,
      letterSpacing: "-0.02em",
      color: "var(--ink-900)"
    }
  }, t.price), t.sub ? /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-sm)",
      color: "var(--ink-500)"
    }
  }, t.sub) : null), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 10,
      margin: "24px 0 26px"
    }
  }, t.features.map(ft => /*#__PURE__*/React.createElement("div", {
    key: ft,
    style: {
      display: "flex",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      color: t.featured ? "var(--blue-600)" : "var(--ink-300)",
      marginTop: 2
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "check",
    size: 15,
    strokeWidth: 2
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-md)",
      color: "var(--ink-700)",
      lineHeight: 1.5
    }
  }, ft)))), /*#__PURE__*/React.createElement(Button, {
    block: true,
    size: "lg",
    variant: t.variant,
    onClick: () => onNavigate(t.featured ? "early" : "start")
  }, t.cta)))));
}
function EarlyAdopter({
  onNavigate
}) {
  const receive = ["INTERA free for the first 12 months", "Custom onboarding", "Direct contact with the INTERA team", "Priority support", "Influence on product development", "Help setting up your first real use case"];
  const expect = ["A real business case", "Feedback", "Readiness to work together and verify our solutions"];
  return /*#__PURE__*/React.createElement(Section, {
    id: "early",
    tone: "inverse",
    pad: 88,
    washes: [{
      color: "var(--wash-blue-dark)",
      x: "84%",
      y: "24%",
      size: 900
    }, {
      color: "var(--wash-teal-dark)",
      x: "6%",
      y: "92%",
      size: 700
    }]
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "minmax(0,1fr) minmax(0,1.15fr)",
      gap: 64,
      alignItems: "start"
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement(SectionHead, {
    inverse: true,
    eyebrow: "Early Adopter programme",
    title: "Help shape INTERA around a real operation",
    lead: "We are looking for a small number of companies and managers ready to use INTERA on their real operational tasks during the beta."
  }), /*#__PURE__*/React.createElement(Button, {
    variant: "inverse",
    size: "lg",
    onClick: () => onNavigate("start")
  }, "I have a problem INTERA could solve")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "minmax(0,1fr) minmax(0,1fr)",
      gap: 28
    }
  }, [["Early Adopters receive", receive, "gift"], ["We expect", expect, "handshake"]].map(([t, list, ic]) => /*#__PURE__*/React.createElement("div", {
    key: t,
    style: {
      background: "rgba(255,255,255,.05)",
      border: "1px solid var(--border-inverse)",
      borderRadius: "var(--radius-card)",
      padding: 24
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xs)",
      fontWeight: 600,
      letterSpacing: "0.09em",
      textTransform: "uppercase",
      color: "rgba(255,255,255,.5)",
      marginBottom: 16
    }
  }, t), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 11
    }
  }, list.map(i => /*#__PURE__*/React.createElement("div", {
    key: i,
    style: {
      display: "flex",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      color: "var(--blue-200)",
      marginTop: 2
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: "check",
    size: 15,
    strokeWidth: 2
  })), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-md)",
      color: "rgba(255,255,255,.82)",
      lineHeight: 1.5
    }
  }, i)))))))));
}
function Partners() {
  const items = [["Roles", "layout-grid"], ["Reconciliations", "scale"], ["Business logic", "workflow"], ["Patterns", "git-branch"], ["Integrations", "plug"], ["Reusable market packages", "package"]];
  return /*#__PURE__*/React.createElement(Section, {
    id: "partners",
    pad: 88
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "minmax(0,1fr) minmax(0,1fr)",
      gap: 64,
      alignItems: "center"
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement(SectionHead, {
    eyebrow: "Partners & resellers",
    title: "Turn your industry knowledge into repeatable solutions",
    lead: "For systems integrators and consultants who already know their customer's real problems."
  }), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-xl)",
      fontWeight: 600,
      letterSpacing: "-0.01em",
      color: "var(--ink-900)",
      marginTop: -20,
      marginBottom: 26
    }
  }, "Solve once. Adapt. Deploy again."), /*#__PURE__*/React.createElement(Button, {
    variant: "secondary",
    size: "lg",
    iconRight: "arrow-right"
  }, "Become an INTERA partner")), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "minmax(0,1fr) minmax(0,1fr)",
      gap: 10
    }
  }, items.map(([label, ic]) => /*#__PURE__*/React.createElement("div", {
    key: label,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10,
      background: "var(--surface-sunken)",
      border: "1px solid var(--border-subtle)",
      borderRadius: "var(--radius-md)",
      padding: "14px 16px"
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: ic,
    size: 16,
    color: "var(--violet-600)"
  }), /*#__PURE__*/React.createElement("span", {
    style: {
      fontSize: "var(--text-md)",
      color: "var(--ink-800)"
    }
  }, label))))));
}
Object.assign(window, {
  Roles,
  ExistingIT,
  StartSmall,
  Pricing,
  EarlyAdopter,
  Partners,
  ROLES
});
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/OfferSections.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/ProductFrame.jsx
try { (() => {
const {
  MetricTile,
  DataTable,
  SignalBadge,
  Badge,
  Icon,
  Tabs,
  StatusDot,
  Logo
} = window.INTERADesignSystem_430dc5;

/**
 * Stand-in for a real product screenshot. The SOW says the three product
 * visuals will be captured from the live system together with the client —
 * until then this frame shows the shape and density a screenshot should have.
 */
/** Fits a fixed-width design into whatever width the column actually has. */
function useElementWidth() {
  const ref = React.useRef(null);
  const [w, setW] = React.useState(0);
  React.useLayoutEffect(() => {
    const el = ref.current;
    if (!el) return;
    const measure = () => setW(el.clientWidth);
    const ro = new ResizeObserver(entries => setW(entries[0].target.clientWidth));
    ro.observe(el);
    measure();
    const raf = requestAnimationFrame(measure);
    window.addEventListener("resize", measure);
    return () => {
      ro.disconnect();
      cancelAnimationFrame(raf);
      window.removeEventListener("resize", measure);
    };
  }, []);
  return [ref, w];
}
function ProductFrame({
  title = "Revenue Assurance",
  children,
  scale,
  designWidth = 1000,
  contentHeight = 458,
  onDark = false,
  caption
}) {
  const [ref, width] = useElementWidth();
  // `scale` caps how large the design may render; the column width decides the rest.
  const maxScale = scale || 1;
  const fit = width ? Math.min(maxScale, width / designWidth) : maxScale;
  const scaled = fit !== 1;
  return /*#__PURE__*/React.createElement("div", {
    ref: ref,
    style: {
      width: "100%"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: designWidth,
      height: scaled ? contentHeight * fit : undefined,
      transform: scaled ? `scale(${fit})` : undefined,
      transformOrigin: "top left"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      background: "var(--surface-card)",
      border: onDark ? "1px solid rgba(255,255,255,.16)" : "1px solid var(--border-default)",
      borderRadius: "var(--radius-lg)",
      boxShadow: onDark ? "var(--shadow-overlay)" : "var(--shadow-lg)",
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      height: 38,
      background: "var(--surface-sunken)",
      borderBottom: "1px solid var(--border-subtle)",
      display: "flex",
      alignItems: "center",
      gap: 10,
      padding: "0 14px"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      gap: 6
    }
  }, ["var(--ink-200)", "var(--ink-200)", "var(--ink-200)"].map((c, i) => /*#__PURE__*/React.createElement("span", {
    key: i,
    style: {
      width: 9,
      height: 9,
      borderRadius: 999,
      background: c
    }
  }))), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      display: "flex",
      justifyContent: "center"
    }
  }, /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)",
      fontSize: 11,
      color: "var(--ink-400)",
      background: "var(--white)",
      border: "1px solid var(--border-subtle)",
      borderRadius: 4,
      padding: "2px 10px"
    }
  }, "app.intera-roles.com / ", title.toLowerCase().replace(/ /g, "-"))), /*#__PURE__*/React.createElement("div", {
    style: {
      width: 40
    }
  })), children)), caption ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xs)",
      color: "var(--ink-400)",
      marginTop: 12
    }
  }, caption) : null);
}
function DashboardPreview() {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      height: 420
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      width: 172,
      borderRight: "1px solid var(--border-subtle)",
      background: "var(--surface-sunken)",
      padding: "14px 10px",
      flex: "none"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      padding: "0 6px 12px"
    }
  }, /*#__PURE__*/React.createElement(Logo, {
    size: 14
  })), [["layout-dashboard", "Overview", false], ["circle-dollar-sign", "Finance Control", false], ["gauge", "Operations", false], ["scale", "Revenue Assurance", true], ["heart-pulse", "Customer Health", false], ["shield-check", "System Integrity", false]].map(([ic, l, on]) => /*#__PURE__*/React.createElement("div", {
    key: l,
    style: {
      display: "flex",
      alignItems: "center",
      gap: 8,
      padding: "7px 8px",
      borderRadius: "var(--radius-sm)",
      background: on ? "var(--white)" : "transparent",
      border: on ? "1px solid var(--border-subtle)" : "1px solid transparent",
      color: on ? "var(--ink-900)" : "var(--ink-600)",
      fontSize: 12.5,
      fontWeight: on ? 500 : 400,
      marginBottom: 2
    }
  }, /*#__PURE__*/React.createElement(Icon, {
    name: ic,
    size: 15
  }), l))), /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      minWidth: 0,
      padding: 18,
      display: "flex",
      flexDirection: "column",
      gap: 14,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      alignItems: "center",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: 17,
      fontWeight: 600,
      letterSpacing: "-0.01em"
    }
  }, "Revenue Assurance"), /*#__PURE__*/React.createElement(StatusDot, {
    tone: "critical",
    label: "3 need attention",
    style: {
      fontSize: 12
    }
  })), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "grid",
      gridTemplateColumns: "repeat(4,minmax(0,1fr))",
      gap: 10
    }
  }, /*#__PURE__*/React.createElement(MetricTile, {
    label: "Unbilled usage",
    value: "\u20AC48,120",
    delta: "+12.4%",
    direction: "up",
    tone: "warning",
    note: "vs last week"
  }), /*#__PURE__*/React.createElement(MetricTile, {
    label: "Reconciled",
    value: "128,904",
    delta: "+0.3%",
    direction: "up",
    tone: "ok",
    note: "today"
  }), /*#__PURE__*/React.createElement(MetricTile, {
    label: "Open incidents",
    value: "3",
    delta: "+2",
    direction: "up",
    tone: "critical",
    note: "24h"
  }), /*#__PURE__*/React.createElement(MetricTile, {
    label: "Coverage",
    value: "94.2",
    unit: "%",
    delta: "0.0%",
    direction: "flat",
    note: "of revenue"
  })), /*#__PURE__*/React.createElement(DataTable, {
    dense: true,
    columns: [{
      key: "type",
      header: "Type",
      width: 132,
      render: r => /*#__PURE__*/React.createElement(SignalBadge, {
        type: r.type,
        size: "sm"
      })
    }, {
      key: "name",
      header: "Subject"
    }, {
      key: "src",
      header: "Source",
      mono: true,
      width: 150
    }, {
      key: "diff",
      header: "Difference",
      align: "right",
      mono: true,
      width: 100
    }, {
      key: "st",
      header: "Status",
      width: 116,
      render: r => /*#__PURE__*/React.createElement(Badge, {
        tone: r.tone
      }, r.st)
    }],
    rows: [{
      type: "incident",
      name: "Usage not billed — 12 Aug",
      src: "billing.usage_daily",
      diff: "€48,120",
      st: "Open",
      tone: "critical"
    }, {
      type: "reconciliation",
      name: "Billing vs mediation volumes",
      src: "mediation.cdr",
      diff: "4,812",
      st: "Investigating",
      tone: "warning"
    }, {
      type: "event",
      name: "Tariff plan changed",
      src: "erp.tariffs",
      diff: "—",
      st: "Noted",
      tone: "neutral"
    }, {
      type: "pattern",
      name: "Month-end exception spike",
      src: "ops.exceptions",
      diff: "×3.1",
      st: "Confirmed",
      tone: "ok"
    }, {
      type: "reconciliation",
      name: "CRM vs ERP customer count",
      src: "crm.accounts",
      diff: "118",
      st: "Resolved",
      tone: "ok"
    }]
  })));
}
Object.assign(window, {
  ProductFrame,
  DashboardPreview,
  useElementWidth
});
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/ProductFrame.jsx", error: String((e && e.message) || e) }); }

// ui_kits/website/SiteChrome.jsx
try { (() => {
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
const {
  Button,
  IconButton,
  Logo,
  Icon,
  Badge
} = window.INTERADesignSystem_430dc5;
const NAV = [{
  id: "how",
  label: "How it works"
}, {
  id: "action",
  label: "In action"
}, {
  id: "roles",
  label: "Roles"
}, {
  id: "pricing",
  label: "Pricing"
}, {
  id: "partners",
  label: "Partners"
}];
function TopNav({
  active,
  onNavigate
}) {
  const [wide, setWide] = React.useState(typeof window === "undefined" || window.innerWidth >= 1080);
  const [open, setOpen] = React.useState(false);
  React.useEffect(() => {
    const on = () => {
      const w = window.innerWidth >= 1080;
      setWide(w);
      if (w) setOpen(false);
    };
    window.addEventListener("resize", on);
    return () => window.removeEventListener("resize", on);
  }, []);
  const go = id => {
    setOpen(false);
    onNavigate(id);
  };
  return /*#__PURE__*/React.createElement("header", {
    style: {
      position: "sticky",
      top: 0,
      zIndex: 40,
      background: "rgba(255,255,255,.92)",
      backdropFilter: "blur(8px)",
      borderBottom: "1px solid var(--border-subtle)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      maxWidth: 1160,
      margin: "0 auto",
      padding: "0 32px",
      height: 76,
      display: "flex",
      alignItems: "center",
      gap: 28,
      overflow: "hidden"
    }
  }, /*#__PURE__*/React.createElement("a", {
    href: "#top",
    onClick: e => {
      e.preventDefault();
      go("top");
    },
    style: {
      borderBottom: 0,
      display: "flex"
    }
  }, /*#__PURE__*/React.createElement(Logo, {
    size: 18
  })), wide ? /*#__PURE__*/React.createElement("nav", {
    style: {
      display: "flex",
      gap: 24,
      flex: 1,
      minWidth: 0
    }
  }, NAV.map(n => /*#__PURE__*/React.createElement("a", {
    key: n.id,
    href: "#" + n.id,
    onClick: e => {
      e.preventDefault();
      go(n.id);
    },
    style: {
      fontSize: "var(--text-md)",
      color: active === n.id ? "var(--ink-900)" : "var(--ink-600)",
      fontWeight: active === n.id ? 500 : 400,
      borderBottom: 0,
      padding: "4px 0",
      whiteSpace: "nowrap",
      flex: "none"
    }
  }, n.label))) : /*#__PURE__*/React.createElement("div", {
    style: {
      flex: 1,
      display: "flex"
    }
  }, /*#__PURE__*/React.createElement(IconButton, {
    icon: open ? "x" : "menu",
    label: open ? "Close menu" : "Open menu",
    variant: "outline",
    "aria-expanded": open,
    onClick: () => setOpen(!open)
  })), /*#__PURE__*/React.createElement(Badge, {
    tone: "info"
  }, "Beta"), wide ? /*#__PURE__*/React.createElement(Button, {
    variant: "ghost"
  }, "Sign in") : null, /*#__PURE__*/React.createElement(Button, {
    size: "lg",
    style: wide ? {
      minWidth: 196
    } : undefined,
    onClick: () => go("early")
  }, "Get Early Access")), !wide && open ? /*#__PURE__*/React.createElement("nav", {
    style: {
      position: "absolute",
      top: "100%",
      left: 0,
      right: 0,
      zIndex: 41,
      borderTop: "1px solid var(--border-subtle)",
      borderBottom: "1px solid var(--border-default)",
      background: "var(--surface-page)",
      boxShadow: "var(--shadow-lg)",
      padding: "8px 32px 16px",
      display: "flex",
      flexDirection: "column"
    }
  }, NAV.map(n => /*#__PURE__*/React.createElement("a", {
    key: n.id,
    href: "#" + n.id,
    onClick: e => {
      e.preventDefault();
      go(n.id);
    },
    style: {
      fontSize: "var(--text-lg)",
      color: active === n.id ? "var(--ink-900)" : "var(--ink-700)",
      fontWeight: active === n.id ? 500 : 400,
      borderBottom: "1px solid var(--border-hairline)",
      padding: "14px 0"
    }
  }, n.label)), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 16
    }
  }, /*#__PURE__*/React.createElement(Button, {
    variant: "secondary",
    size: "lg",
    block: true
  }, "Sign in"))) : null);
}

/** Ambient wash: one soft, very large tint behind a section. Never on a component. */
function Wash({
  color,
  x = "50%",
  y = "50%",
  size = 900,
  opacity = 1
}) {
  return /*#__PURE__*/React.createElement("div", {
    "aria-hidden": "true",
    style: {
      position: "absolute",
      left: x,
      top: y,
      width: size,
      height: size,
      transform: "translate(-50%,-50%)",
      pointerEvents: "none",
      opacity,
      background: `radial-gradient(circle, ${color} 0%, transparent 68%)`
    }
  });
}
function Section({
  id,
  tone = "page",
  pad = 96,
  washes,
  children,
  style
}) {
  const bg = tone === "sunken" ? "var(--surface-sunken)" : tone === "inverse" ? "var(--surface-inverse)" : "var(--surface-page)";
  return /*#__PURE__*/React.createElement("section", {
    id: id,
    style: {
      position: "relative",
      overflow: "hidden",
      background: bg,
      borderTop: tone === "sunken" ? "1px solid var(--border-subtle)" : "none",
      ...style
    }
  }, washes ? washes.map((w, i) => /*#__PURE__*/React.createElement(Wash, _extends({
    key: i
  }, w))) : null, /*#__PURE__*/React.createElement("div", {
    style: {
      position: "relative",
      maxWidth: 1160,
      margin: "0 auto",
      padding: `${pad}px 32px`
    }
  }, children));
}
function SectionHead({
  eyebrow,
  title,
  lead,
  align = "left",
  inverse = false,
  maxWidth = 720
}) {
  return /*#__PURE__*/React.createElement("div", {
    style: {
      maxWidth,
      margin: align === "center" ? "0 auto" : 0,
      textAlign: align,
      marginBottom: 44
    }
  }, eyebrow ? /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xs)",
      fontWeight: 600,
      letterSpacing: "0.09em",
      textTransform: "uppercase",
      color: inverse ? "var(--blue-200)" : "var(--blue-600)",
      marginBottom: 14
    }
  }, eyebrow) : null, /*#__PURE__*/React.createElement("h2", {
    style: {
      fontSize: "var(--text-3xl)",
      fontWeight: 600,
      letterSpacing: "-0.01em",
      lineHeight: 1.22,
      color: inverse ? "var(--white)" : "var(--ink-900)"
    }
  }, title), lead ? /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-lg)",
      lineHeight: 1.6,
      color: inverse ? "rgba(255,255,255,.72)" : "var(--ink-600)",
      marginTop: 16
    }
  }, lead) : null);
}
function SiteFooter({
  onNavigate
}) {
  const cols = [["Product", ["How it works", "INTERA Roles", "Working with IT", "Pricing"]], ["Programme", ["Early Adopter", "Bring us a problem", "Partners & resellers"]], ["Company", ["About", "Contact", "Privacy"]]];
  return /*#__PURE__*/React.createElement("footer", {
    style: {
      background: "var(--surface-inverse)",
      color: "rgba(255,255,255,.72)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      maxWidth: 1160,
      margin: "0 auto",
      padding: "64px 32px 40px",
      display: "grid",
      gridTemplateColumns: "1.6fr 1fr 1fr 1fr",
      gap: 48
    }
  }, /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement(Logo, {
    size: 18,
    tone: "inverse"
  }), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: "var(--text-md)",
      lineHeight: 1.6,
      marginTop: 16,
      maxWidth: 300
    }
  }, "One operating picture across the systems your teams already use."), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: 24
    }
  }, /*#__PURE__*/React.createElement(Button, {
    variant: "inverse",
    size: "lg",
    style: {
      minWidth: 248
    },
    onClick: () => onNavigate("early")
  }, "Become an Early Adopter"))), cols.map(([t, items]) => /*#__PURE__*/React.createElement("div", {
    key: t
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      fontSize: "var(--text-xs)",
      fontWeight: 600,
      letterSpacing: "0.09em",
      textTransform: "uppercase",
      color: "rgba(255,255,255,.45)",
      marginBottom: 14
    }
  }, t), /*#__PURE__*/React.createElement("div", {
    style: {
      display: "flex",
      flexDirection: "column",
      gap: 9
    }
  }, items.map(i => /*#__PURE__*/React.createElement("a", {
    key: i,
    href: "#top",
    onClick: e => e.preventDefault(),
    style: {
      fontSize: "var(--text-md)",
      color: "rgba(255,255,255,.72)",
      borderBottom: 0
    }
  }, i)))))), /*#__PURE__*/React.createElement("div", {
    style: {
      borderTop: "1px solid var(--border-inverse)"
    }
  }, /*#__PURE__*/React.createElement("div", {
    style: {
      maxWidth: 1160,
      margin: "0 auto",
      padding: "20px 32px",
      display: "flex",
      justifyContent: "space-between",
      fontSize: "var(--text-xs)",
      color: "rgba(255,255,255,.45)"
    }
  }, /*#__PURE__*/React.createElement("span", null, "\xA9 2026 INTERA"), /*#__PURE__*/React.createElement("span", {
    style: {
      fontFamily: "var(--font-mono)"
    }
  }, "intera-roles.com"))));
}
Object.assign(window, {
  TopNav,
  Section,
  SectionHead,
  SiteFooter,
  Wash,
  NAV
});
})(); } catch (e) { __ds_ns.__errors.push({ path: "ui_kits/website/SiteChrome.jsx", error: String((e && e.message) || e) }); }

__ds_ns.Badge = __ds_scope.Badge;

__ds_ns.Button = __ds_scope.Button;

__ds_ns.Card = __ds_scope.Card;

__ds_ns.CardHeader = __ds_scope.CardHeader;

__ds_ns.Icon = __ds_scope.Icon;

__ds_ns.IconButton = __ds_scope.IconButton;

__ds_ns.Logo = __ds_scope.Logo;

__ds_ns.Tag = __ds_scope.Tag;

__ds_ns.DataTable = __ds_scope.DataTable;

__ds_ns.MetricTile = __ds_scope.MetricTile;

__ds_ns.SIGNALS = __ds_scope.SIGNALS;

__ds_ns.SignalBadge = __ds_scope.SignalBadge;

__ds_ns.SignalChain = __ds_scope.SignalChain;

__ds_ns.Alert = __ds_scope.Alert;

__ds_ns.Dialog = __ds_scope.Dialog;

__ds_ns.StatusDot = __ds_scope.StatusDot;

__ds_ns.Toast = __ds_scope.Toast;

__ds_ns.ToastStack = __ds_scope.ToastStack;

__ds_ns.Tooltip = __ds_scope.Tooltip;

__ds_ns.Checkbox = __ds_scope.Checkbox;

__ds_ns.Field = __ds_scope.Field;

__ds_ns.Input = __ds_scope.Input;

__ds_ns.Radio = __ds_scope.Radio;

__ds_ns.Select = __ds_scope.Select;

__ds_ns.Switch = __ds_scope.Switch;

__ds_ns.Textarea = __ds_scope.Textarea;

__ds_ns.Tabs = __ds_scope.Tabs;

})();
