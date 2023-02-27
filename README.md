# General

now you can save MentionedMessage in UI

Example: You Chat Hi @MulqiGaming @Friends

it will be save Message later to MulqiGaming and Friends

# Commands

Commands For Getting all Mentioned Message:

Commands | Aliases | Default
--- | --- | ---
`/mymessage` | `/mm` | True

# Screenshot

![Screenshot](https://github.com/XanderID/MentionedMessage/blob/6db7af686c18e22c79832e974167c1c430c6a88e/Screenshot.png)

# Feature
- You can clear All Mentioned Message
- Can Multiple Mention Player
- can Mention Players offline
- can set content format in config
- can set custom timezone

# Config

``` YAML

---
# Timezone
timezone: "asia/jakarta"

# Auto Save Interval Message
# Seconds
save-interval: 180

# Tag
# {MSG} Messages
# {YEARS} yyyy Years Message
# {MONTH} 1-12 Month Message
# {DATE} 1-31 Date Message
# {HOURS} Hours Time Message
# {MINUTES} Minutes Time Message
# {NAME} Name Who Mentioned

# Mention Message Format
# Content
mention-message: "§f[§a{HOURS}:{MINUTES}§r] From §a{NAME}: §f{MSG}"
...
```

# Additional Notes

- If you find bugs or want to give suggestions, please visit [here](https://github.com/XanderID/MentionedMessage/issues)
- Icons By [icons8.com](https://icons8.com)
