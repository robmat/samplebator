#import "SplashVC.h"
#import "SavedSearchVC.h"
#import "AbiltiesVC.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequest.h"

@implementation SplashVC

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	[NSTimer scheduledTimerWithTimeInterval:0.5 target:self selector:@selector(timerAction) userInfo:nil repeats:NO];
}
- (void)timerAction {
	ASIHTTPRequest* req = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/amiloggedin"]];
	[req startSynchronous];
	CXMLDocument* doc = [[CXMLDocument alloc] initWithData:[req responseData] options:0 error:nil];
	BOOL loggedIn = [[[[doc nodesForXPath:@"/AmILoggedIn/LoggedIn" error:nil] objectAtIndex:0] stringValue] isEqualToString:@"true"];
	[doc release];
	
	req = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/listcvs"]];
	[req startSynchronous];
	doc = [[CXMLDocument alloc] initWithData:[req responseData] options:0 error:nil];
	BOOL uploadedCV = [[doc nodesForXPath:@"/CVList/CV" error:nil] count] > 0;
	[doc release];
	
	if (loggedIn && uploadedCV) {
		SavedSearchVC* mmvc = [[SavedSearchVC alloc] init];
		[self.navigationController pushViewController:mmvc animated:YES];
		[mmvc release];
	} else {
		AbiltiesVC* avc = [[AbiltiesVC alloc] init];
		avc->loggedIn = loggedIn;
		avc->uploadedCV = uploadedCV;
		[self.navigationController pushViewController:avc animated:YES];
		[avc release];
	}

}
- (void)dealloc {
    [super dealloc];
}


@end
