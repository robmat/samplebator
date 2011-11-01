#import "VCBase.h"
#import <AVFoundation/AVFoundation.h>
#import "AppInfoVC.h"
#import "WebViewVC.h"
#import <MessageUI/MessageUI.h>

@implementation VCBase

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
	shouldIPlayPlak = YES;
	return [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	if (shouldIPlayPlak) {
		NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
		avPlayer = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
		[avPlayer play];
	}
	backBtn = [[UIButton buttonWithType:UIButtonTypeCustom] retain];
	backBtn.frame = CGRectMake(5, 5, 72, 31);
	[backBtn setImage:[UIImage imageNamed:@"back_btn.png"] forState:UIControlStateNormal];
	[backBtn addTarget:self action:@selector(backAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:backBtn];
}
- (void)setUpTabBarButtons {
	UIButton* btn = [UIButton buttonWithType:UIButtonTypeCustom];
	btn.frame = CGRectMake(30, 376, 45, 37);
	[btn addTarget:self action:@selector(infoAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:btn];
	
	btn = [UIButton buttonWithType:UIButtonTypeCustom];
	btn.frame = CGRectMake(243, 376, 45, 37);
	[btn addTarget:self action:@selector(newsAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:btn];
	
	btn = [UIButton buttonWithType:UIButtonTypeCustom];
	btn.frame = CGRectMake(98, 376, 45, 37);
	[btn addTarget:self action:@selector(mailAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:btn];
}
- (void)mailComposeController:(MFMailComposeViewController*)controller  
          didFinishWithResult:(MFMailComposeResult)result 
                        error:(NSError*)error {
	if (result == MFMailComposeResultCancelled) {
		[self dismissModalViewControllerAnimated:YES];
		return;
	}
	if (result == MFMailComposeResultSent) {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Mail sent." 
														message:@"Sending the mail succeeded." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Sending the mail failed for unknown reason, try again later." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
	[self dismissModalViewControllerAnimated:YES];
}
- (void)mailAction: (id) sender {
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	if ([MFMailComposeViewController canSendMail] && controller != nil)	{
		controller.mailComposeDelegate = self;
		[controller setToRecipients:[NSArray arrayWithObjects:@"headoffice@rheadgroup.com", nil]];
		[controller setSubject:@""];
		[controller setMessageBody:@"" isHTML:NO]; 
		[self presentModalViewController:controller animated:YES];
		[controller release];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Can't send mail, probably no email account is set up." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
}	
- (void)infoAction: (id) sender {
	AppInfoVC* aivc = [[AppInfoVC alloc] init];
	[self.navigationController pushViewController:aivc animated:YES];
	[aivc release];
}
- (void)newsAction: (id) sender {
	WebViewVC* wwvc = [[WebViewVC alloc] init];
	wwvc.url = @"http://www.rheadgroup.com/newshome.asp";
	wwvc->dontAppendPass = YES;
	[self.navigationController pushViewController:wwvc animated:YES];
	[wwvc release];
}
- (void) animateView: (UIView*) uiview up: (BOOL) up distance: (int) movementDistance {
    //const int movementDistance = 90; // tweak as needed
    const float movementDuration = 0.3f; // tweak as needed
    int movement = (up ? -movementDistance : movementDistance);
    [UIView beginAnimations: @"anim" context: nil];
    [UIView setAnimationBeginsFromCurrentState: YES];
    [UIView setAnimationDuration: movementDuration];
    uiview.frame = CGRectOffset(uiview.frame, 0, movement);
    [UIView commitAnimations];
}
- (void)backAction: (id) sender {
	[self.navigationController popViewControllerAnimated:YES];
}
- (void) hideBackBtn {
	backBtn.hidden = YES;
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)playPlak {
	[avPlayer play];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}

- (void)dealloc {
    [super dealloc];
	[avPlayer release];
	[backBtn release];
}

@end
