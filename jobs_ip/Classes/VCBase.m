#import "VCBase.h"
#import <AVFoundation/AVFoundation.h>
#import "MainMenuVC.h"

@implementation VCBase

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
	shouldIPlayPlak = YES;
	return [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	self.navigationController.navigationBarHidden = YES;
	if (shouldIPlayPlak) {
		NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
		avPlayer = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
		[avPlayer play];
	}
	backBtn = [[UIButton buttonWithType:UIButtonTypeCustom] retain];
	backBtn.frame = CGRectMake(9, 8, 58, 29);
	[backBtn setBackgroundImage:[UIImage imageNamed:@"back_btn.png"] forState:UIControlStateNormal];
	[backBtn addTarget:self action:@selector(backAction:) forControlEvents:UIControlEventTouchUpInside];
	//[self.view addSubview:backBtn];
	[self setUpTabButtons];
	UIBarButtonItem *backButton = [[UIBarButtonItem alloc] initWithTitle:@"Back" style:UIBarButtonItemStylePlain target:nil action:nil];
	self.navigationItem.backBarButtonItem = backButton;
	[backButton release];
}
- (void)setUpTabButtons {
	tabMainMenuBtn = [[UIButton buttonWithType:UIButtonTypeCustom] retain];
	tabMainMenuBtn.frame = CGRectMake(8, 418, 45, 37);
	[tabMainMenuBtn addTarget:self action:@selector(tabAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:tabMainMenuBtn];
}
- (void)tabAction: (id) sender {
	if (sender == tabMainMenuBtn) {
		MainMenuVC* ssvc = [[MainMenuVC alloc] init];
		[self.navigationController pushViewController:ssvc animated:YES];
		[ssvc release];
	}
}
- (void)animateView: (UIView*) uiview up: (BOOL) up distance: (int) movementDistance {
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
- (void)hideTabButtons {
	tabMainMenuBtn.hidden = YES;
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
	[tabMainMenuBtn release];
}

@end
