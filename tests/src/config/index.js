import path from "path";

const rootDir = path.normalize(path.join(__dirname, '../..'));

module.exports = {
    rootDir,
    downloadDir: path.join(rootDir, 'downloads'),
    url: process.env.WEB_URL || "http://microsimulation.pub/",
    iiifPort: process.env.IIIF_PORT || 8081,
    headless: {
        mode: !!process.env.HEADLESS_MODE || false,
        windowSize: {
            width: 1920,
            height: 1080
        }
    }
}