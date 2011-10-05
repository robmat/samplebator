#import "VCBase.h"
#import <AVFoundation/AVFoundation.h>
#import "AboutVC.h"
#import "CategoriesVC.h"
#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>
#import <MessageUI/MFMailComposeViewController.h>

@implementation VCBase

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
	shouldIPlayPlak = YES;
	return [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
}
- (IBAction)aboutAction: (id) sender {
	AboutVC* avc = [[AboutVC alloc] init];
	[self.navigationController pushViewController:avc animated:YES];
	[avc release];
}
- (IBAction)categoriesAction: (id) sender {
	CategoriesVC* cvc = [[CategoriesVC alloc] init];
	[self.navigationController pushViewController:cvc animated:YES];
	[cvc release];
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
- (void) feedbackAction: (id) sender {
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	if ([MFMailComposeViewController canSendMail] && controller != nil)	{
		controller.mailComposeDelegate = self;
		[controller setSubject:@"My feedback subject"];
		[controller setToRecipients:[NSArray arrayWithObjects:@"s.v.rook@bath.ac.uk", nil]];
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
- (void)viewDidLoad {
    [super viewDidLoad];
	self.navigationController.navigationBarHidden = YES;
	if (shouldIPlayPlak) {
		NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
		avPlayer = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
		[avPlayer play];
	}
	backBtn = [[UIButton buttonWithType:UIButtonTypeRoundedRect] retain];
	backBtn.frame = CGRectMake(4, 4, 72, 37);
	[backBtn setTitle:@"Back" forState:UIControlStateNormal];
	[backBtn addTarget:self action:@selector(backAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:backBtn];
	self.navigationController.navigationBar.barStyle = UIBarStyleBlack;
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
