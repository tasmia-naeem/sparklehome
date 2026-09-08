from fastapi import FastAPI, Request, Form
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles
from fastapi.templating import Jinja2Templates


# Initialize FastAPI application
app = FastAPI()


# Connect static folder for CSS, JavaScript and images
app.mount("/static", StaticFiles(directory="static"), name="static")


# Connect templates folder
templates = Jinja2Templates(directory="templates")


# Home page
@app.get("/", response_class=HTMLResponse)
async def home(request: Request):

    return templates.TemplateResponse(
        request=request,
        name="index.html",
        context={}
    )

    # Inquiry Form
@app.post("/submit-inquiry", response_class=HTMLResponse)
async def submit_inquiry(
    request: Request,
    name: str = Form(...),
    email: str = Form(...),
    phone: str = Form(...),
    service: str = Form(...),
    message: str = Form("")
):

    return HTMLResponse(
        content=f"""
        <html>
        <head>
            <title>Inquiry Submitted</title>
        </head>

        <body style="font-family: Arial; text-align: center; padding: 80px;">

            <h1 style="color: #0f766e;">
                Thank You, {name}!
            </h1>

            <p>Your cleaning inquiry has been received.</p>

            <p><strong>Service:</strong> {service}</p>

            <p>We will contact you soon.</p>

            <a href="/" style="
                display: inline-block;
                margin-top: 20px;
                padding: 12px 25px;
                background: #0f766e;
                color: white;
                text-decoration: none;
                border-radius: 10px;
            ">
                Back to Home
            </a>

        </body>
        </html>
        """
    )