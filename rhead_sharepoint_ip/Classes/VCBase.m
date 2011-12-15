#import "VCBase.h"
#import <AVFoundation/AVFoundation.h>
#import "AppInfoVC.h"
#import "WebViewVC.h"

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
        [avPlayer release];
	}
	backBtn = [[UIButton buttonWithType:UIButtonTypeCustom] retain];
	backBtn.frame = CGRectMake(5, 5, 72, 31);
	[backBtn setImage:[UIImage imageNamed:@"back_btn.png"] forState:UIControlStateNormal];
	[backBtn addTarget:self action:@selector(backAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:backBtn];
    UIImageView* backgroundImg = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"background.png"]];
    backgroundImg.frame = CGRectMake(-1, -1, 481, 481);
    [self.view addSubview:backgroundImg];
    [self.view sendSubviewToBack:backgroundImg];
    [backgroundImg release];
}
- (void)setUpTabBarButtons {
	infoBtn = [UIButton buttonWithType:UIButtonTypeCustom];
	infoBtn.frame = CGRectMake(30, 376, 45, 37);
	[infoBtn addTarget:self action:@selector(infoAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:infoBtn];
	
	newsBtn = [UIButton buttonWithType:UIButtonTypeCustom];
	newsBtn.frame = CGRectMake(243, 376, 45, 37);
	[newsBtn addTarget:self action:@selector(newsAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:newsBtn];
	
	contactBtn = [UIButton buttonWithType:UIButtonTypeCustom];
	contactBtn.frame = CGRectMake(98, 376, 45, 37);
	[contactBtn addTarget:self action:@selector(mailAction:) forControlEvents:UIControlEventTouchUpInside];
	[self.view addSubview:contactBtn];
}
- (void)infoAction: (id) sender {
	AppInfoVC* aivc = [[AppInfoVC alloc] init];
	[self.navigationController pushViewController:aivc animated:YES];
	[aivc release];
}
- (void)newsAction: (id) sender {
	WebViewVC* wwvc = [[WebViewVC alloc] init];
	wwvc.url = @"http://www.rheadgroup.com/newshome.asp";
    wwvc.title = @"News";
	wwvc->dontAppendPass = YES;
	[self.navigationController pushViewController:wwvc animated:YES];
    wwvc->newsBtn.hidden = YES;
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
	//[avPlayer play];
    NSString *path = [[NSBundle mainBundle] pathForResource:@"plak" ofType:@"wav"];
    avPlayer = [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:NULL];
    [avPlayer play];
    [avPlayer release];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (void)dealloc {
    [super dealloc];
	//[avPlayer release];
	//[backBtn release];
}

@end
